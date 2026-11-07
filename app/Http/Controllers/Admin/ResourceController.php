<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioMessage;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Services\MediaLibrary;
use App\Support\AdminResources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __construct(private readonly MediaLibrary $media) {}

    public function index(string $resource): View|RedirectResponse
    {
        $definition = AdminResources::get($resource);
        $model = AdminResources::model($definition['model']);

        if ($definition['single'] ?? false) {
            $item = $model->newQuery()->first() ?? $model->newQuery()->create([]);

            return redirect()->route('admin.resources.edit', [$resource, $item->getKey()]);
        }

        $items = $model->newQuery()->latest()->paginate(15)->withQueryString();

        return view('admin.resources.index', compact('resource', 'definition', 'items'));
    }

    public function create(string $resource): View
    {
        $definition = AdminResources::get($resource);
        abort_if(($definition['readonly'] ?? false) || ($definition['single'] ?? false), 404);

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'item' => null,
            'options' => $this->options($definition),
        ]);
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $definition = AdminResources::get($resource);
        abort_if(($definition['readonly'] ?? false) || ($definition['single'] ?? false), 404);
        $data = $this->validatedData($request, $definition);
        $this->storeUploads($request, $definition, $data);
        $this->normalizeSlug($resource, $data);
        $item = AdminResources::model($definition['model']);
        $item->fill($data)->save();

        if ($item instanceof Project) {
            $this->saveGallery($request, $item);
        }

        return redirect()->route('admin.resources.index', $resource)->with('success', $definition['singular'].' berhasil ditambahkan.');
    }

    public function edit(string $resource, string $record): View|RedirectResponse
    {
        $definition = AdminResources::get($resource);
        $item = AdminResources::model($definition['model'])->newQuery()->findOrFail($record);

        if ($item instanceof PortfolioMessage && ! $item->read_at) {
            $item->forceFill(['read_at' => now()])->save();
        }

        if ($definition['readonly'] ?? false) {
            return view('admin.resources.show', compact('resource', 'definition', 'item'));
        }

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'item' => $item,
            'options' => $this->options($definition),
        ]);
    }

    public function update(Request $request, string $resource, string $record): RedirectResponse
    {
        $definition = AdminResources::get($resource);
        abort_if($definition['readonly'] ?? false, 404);
        $item = AdminResources::model($definition['model'])->newQuery()->findOrFail($record);
        $data = $this->validatedData($request, $definition, $item);
        $this->storeUploads($request, $definition, $data);
        $this->normalizeSlug($resource, $data, $item);
        $item->fill($data)->save();

        if ($item instanceof Project) {
            $this->saveGallery($request, $item);
        }

        return redirect()->route('admin.resources.index', $resource)->with('success', $definition['singular'].' berhasil diperbarui.');
    }

    public function destroy(string $resource, string $record): RedirectResponse
    {
        $definition = AdminResources::get($resource);
        abort_if($definition['single'] ?? false, 404);
        $item = AdminResources::model($definition['model'])->newQuery()->findOrFail($record);
        $item->delete();

        return redirect()->route('admin.resources.index', $resource)->with('success', $definition['singular'].' berhasil dihapus.');
    }

    private function validatedData(Request $request, array $definition, ?Model $item = null): array
    {
        $rules = [];
        foreach ($definition['fields'] as $name => $field) {
            if ($field['type'] === 'gallery') {
                $rules[$name] = $field['rules'];
                $rules[$name.'.*'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';

                continue;
            }

            $rule = $field['rules'];
            if ($item && str_contains($rule, 'unique:')) {
                $rule = preg_replace('/(unique:[^,|]+,[^,|]+)/', '$1,'.$item->getKey(), $rule);
            }
            $rules[$name] = $rule;
        }

        $data = Validator::make($request->all(), $rules)->validate();
        foreach ($definition['fields'] as $name => $field) {
            if ($field['type'] === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }
        }

        unset($data['project_gallery']);

        return $data;
    }

    private function storeUploads(Request $request, array $definition, array &$data): void
    {
        foreach ($definition['fields'] as $name => $field) {
            if (! in_array($field['type'], ['image', 'pdf'], true)) {
                continue;
            }

            if ($request->hasFile($name)) {
                $data[$name] = $this->media->store($request->file($name), $field['label']);

                continue;
            }

            unset($data[$name]);
        }
    }

    private function normalizeSlug(string $resource, array &$data, ?Model $item = null): void
    {
        $slugField = match ($resource) {
            'categories' => 'slug',
            'projects' => 'slug',
            default => null,
        };
        if (! $slugField) {
            return;
        }

        $source = $resource === 'categories' ? ($data['name'] ?? $item?->name) : ($data['title'] ?? $item?->title);
        $slug = filled($data[$slugField] ?? null) ? Str::slug($data[$slugField]) : Str::slug($source);
        $model = AdminResources::get($resource)['model'];
        $query = $model::query()->where($slugField, $slug);
        if ($item) {
            $query->whereKeyNot($item->getKey());
        }
        $base = $slug;
        $suffix = 2;
        while ($query->exists()) {
            $slug = $base.'-'.$suffix++;
            $query = $model::query()->where($slugField, $slug);
            if ($item) {
                $query->whereKeyNot($item->getKey());
            }
        }
        $data[$slugField] = $slug;
    }

    private function saveGallery(Request $request, Project $project): void
    {
        $removeIds = $request->input('remove_gallery', []);
        if (is_array($removeIds) && $removeIds !== []) {
            $project->images()->whereIn('id', $removeIds)->delete();
        }

        foreach ($request->file('project_gallery', []) as $file) {
            $path = $this->media->store($file, $project->title);
            $project->images()->create(['path' => $path, 'alt' => $project->title]);
        }
    }

    private function options(array $definition): array
    {
        $options = [];
        foreach ($definition['fields'] as $field) {
            if (! isset($field['options']) || ! is_string($field['options'])) {
                continue;
            }
            $options[$field['options']] = $field['options'] === 'categories'
                ? ProjectCategory::query()->orderBy('name')->pluck('name', 'id')->all()
                : $field['options'];
        }

        return $options;
    }
}
