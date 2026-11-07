<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Experience;
use App\Models\MediaAsset;
use App\Models\PortfolioMessage;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Services\MediaLibrary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_portfolio_renders_seeded_content_and_profile_placeholder(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Dashboard Keuangan')
            ->assertSee('Placeholder foto profil', false);
    }

    public function test_admin_routes_require_admin_authentication(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));

        $nonAdmin = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@example.test',
            'password' => 'a-strong-password',
            'is_admin' => false,
        ]);

        $this->actingAs($nonAdmin)->get('/admin')->assertForbidden();
    }

    public function test_seeded_admin_can_sign_in_and_change_the_password(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $initialPassword = env('ADMIN_PASSWORD', 'Password123!');
        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => $initialPassword,
        ])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->put(route('admin.account.update'), [
            'name' => 'Admin Baru',
            'email' => 'admin@example.com',
            'current_password' => $initialPassword,
            'password' => 'NewStrongPassword123!',
            'password_confirmation' => 'NewStrongPassword123!',
        ])->assertRedirect();

        $admin->refresh();
        $this->assertTrue(Hash::check('NewStrongPassword123!', $admin->password));
    }

    public function test_admin_can_create_and_update_skills(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->actingAs($admin);

        $this->post(route('admin.resources.store', 'skills'), [
            'name' => 'Alpine.js',
            'category' => 'Frontend',
            'level' => 75,
            'sort_order' => 15,
            'active' => '1',
        ])->assertRedirect(route('admin.resources.index', 'skills'));

        $skill = Skill::where('name', 'Alpine.js')->firstOrFail();
        $this->assertSame(75, $skill->level);
        $this->assertTrue($skill->active);

        $this->put(route('admin.resources.update', ['skills', $skill->id]), [
            'name' => 'Alpine.js',
            'category' => 'JavaScript',
            'level' => 82,
            'sort_order' => 15,
            'active' => '1',
        ])->assertRedirect(route('admin.resources.index', 'skills'));

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'category' => 'JavaScript', 'level' => 82]);
    }

    public function test_project_can_save_and_display_a_github_link(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $project = Project::where('slug', 'company-profile')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.resources.update', ['projects', $project->id]), [
            'title' => $project->title,
            'slug' => $project->slug,
            'category_id' => $project->category_id,
            'summary' => $project->summary,
            'description' => $project->description,
            'technologies' => $project->technologies,
            'demo_url' => 'https://demo.example.com',
            'repository_url' => 'https://github.com/example/company-profile',
            'sort_order' => $project->sort_order,
            'featured' => '1',
        ])->assertRedirect(route('admin.resources.index', 'projects'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'repository_url' => 'https://github.com/example/company-profile',
        ]);
        $this->get('/')
            ->assertOk()
            ->assertSee('>GitHub</a>', false)
            ->assertSee('https://github.com/example/company-profile', false);
    }

    public function test_achievement_can_be_managed_and_rendered_on_the_public_page(): void
    {
        Storage::fake('public');
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.resources.store', 'achievements'), [
            'title' => 'Juara Kompetisi Web',
            'organization' => 'Komunitas Developer',
            'description' => 'Meraih juara pertama dalam kompetisi pengembangan web.',
            'achieved_at' => '2025-08-15',
            'image_path' => UploadedFile::fake()->image('award.png')->size(200),
            'sort_order' => 1,
            'featured' => '1',
        ])->assertRedirect(route('admin.resources.index', 'achievements'));

        $achievement = Achievement::where('title', 'Juara Kompetisi Web')->firstOrFail();
        Storage::disk('public')->assertExists($achievement->image_path);
        $this->get('/')
            ->assertOk()
            ->assertSee('Juara Kompetisi Web')
            ->assertSee('Komunitas Developer')
            ->assertSee('storage/'.$achievement->image_path, false);

        $this->get(route('admin.resources.create', 'achievements'))->assertOk();
        $this->assertTrue(app(MediaLibrary::class)->isInUse($achievement->image_path));
    }

    public function test_admin_content_pages_and_forms_are_available(): void
    {
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        foreach (['statistics', 'educations', 'experiences', 'achievements', 'skills', 'categories', 'projects', 'processes', 'messages', 'settings'] as $resource) {
            $this->get(route('admin.resources.index', $resource))->assertOk();
        }

        foreach (['statistics', 'educations', 'experiences', 'achievements', 'skills', 'categories', 'projects', 'processes', 'settings'] as $resource) {
            $this->get(route('admin.resources.create', $resource))->assertOk();
        }

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.media.index'))->assertOk();
        $this->get(route('admin.account.edit'))->assertOk();
    }

    public function test_admin_can_upload_media_and_cannot_delete_media_used_by_profile(): void
    {
        Storage::fake('public');
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        $this->post(route('admin.media.store'), [
            'file' => UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF"),
            'alt_text' => 'Curriculum vitae',
        ])->assertRedirect();

        $asset = MediaAsset::firstOrFail();
        Storage::disk('public')->assertExists($asset->path);

        Profile::firstOrFail()->update(['cv_path' => $asset->path]);
        $this->delete(route('admin.media.destroy', $asset))
            ->assertSessionHasErrors('media');
        $this->assertDatabaseHas('media_assets', ['id' => $asset->id]);

        Profile::firstOrFail()->update(['cv_path' => null]);
        $this->delete(route('admin.media.destroy', $asset))->assertRedirect();
        $this->assertDatabaseMissing('media_assets', ['id' => $asset->id]);
        Storage::disk('public')->assertMissing($asset->path);
    }

    public function test_profile_photo_upload_is_used_by_the_public_website(): void
    {
        Storage::fake('public');
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $profile = Profile::firstOrFail();

        $this->actingAs($admin)->put(route('admin.resources.update', ['profile', $profile->id]), [
            'name' => $profile->name,
            'brand' => $profile->brand,
            'profession' => $profile->profession,
            'tagline' => $profile->tagline,
            'short_bio' => $profile->short_bio,
            'biography' => $profile->biography,
            'email' => $profile->email,
            'location' => $profile->location,
            'photo_path' => UploadedFile::fake()->image('profile.png')->size(200),
        ])->assertRedirect(route('admin.resources.index', 'profile'));

        $profile->refresh();
        $this->assertNotNull($profile->photo_path);
        Storage::disk('public')->assertExists($profile->photo_path);
        $this->get('/')->assertOk()->assertSee('storage/'.$profile->photo_path, false);
    }

    public function test_experience_image_is_optional_and_uploaded_image_is_shown_publicly(): void
    {
        Storage::fake('public');
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->actingAs($admin);

        $this->post(route('admin.resources.store', 'experiences'), [
            'company' => 'Studio Contoh',
            'position' => 'Web Developer',
            'start_date' => '2024',
            'end_date' => '2025',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.resources.index', 'experiences'));

        $experience = Experience::where('company', 'Studio Contoh')->firstOrFail();
        $this->assertNull($experience->image_path);

        $this->put(route('admin.resources.update', ['experiences', $experience->id]), [
            'company' => 'Studio Contoh',
            'position' => 'Web Developer',
            'start_date' => '2024',
            'end_date' => '2025',
            'sort_order' => 1,
            'image_path' => UploadedFile::fake()->image('experience.png')->size(200),
        ])->assertRedirect(route('admin.resources.index', 'experiences'));

        $experience->refresh();
        $this->assertNotNull($experience->image_path);
        Storage::disk('public')->assertExists($experience->image_path);
        $this->get('/')->assertOk()->assertSee('storage/'.$experience->image_path, false);
    }

    public function test_public_contact_form_stores_message_for_admin_inbox(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Pengunjung',
            'email' => 'visitor@example.test',
            'subject' => 'Kolaborasi',
            'message' => 'Mari berdiskusi tentang proyek.',
        ])->assertRedirect('/#kontak');

        $this->assertDatabaseHas('messages', [
            'email' => 'visitor@example.test',
            'subject' => 'Kolaborasi',
        ]);
        $this->assertSame(1, PortfolioMessage::count());
    }
}
