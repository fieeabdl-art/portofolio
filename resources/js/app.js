import './bootstrap';

document.querySelectorAll('[data-n]').forEach((element) => {
    const target = Number(element.dataset.n);
    let startedAt;
    const animate = (timestamp) => {
        startedAt ??= timestamp;
        const progress = Math.min((timestamp - startedAt) / 1400, 1);
        element.textContent = `${Math.round(target * (1 - (1 - progress) ** 3))}${element.dataset.suffix ?? '+'}`;
        if (progress < 1) requestAnimationFrame(animate);
    };
    requestAnimationFrame(animate);
});

document.querySelectorAll('.glass').forEach((glass) => {
    glass.addEventListener('pointermove', (event) => {
        const bounds = glass.getBoundingClientRect();
        glass.style.setProperty('--mx', `${event.clientX - bounds.left}px`);
        glass.style.setProperty('--my', `${event.clientY - bounds.top}px`);
    });
});

const cards = [...document.querySelectorAll('.project-card')];
document.querySelectorAll('[data-filter]').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('[data-filter]').forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
        cards.forEach((card) => {
            const show = button.dataset.filter === 'all' || card.dataset.category === button.dataset.filter;
            card.classList.toggle('off', !show);
        });
    });
});

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

document.querySelectorAll('[data-preview-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const preview = document.querySelector(input.dataset.previewInput);
        if (!preview || !input.files?.[0]) return;
        const file = input.files[0];
        preview.replaceChildren();
        if (file.type.startsWith('image/')) {
            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = 'Pratinjau gambar yang dipilih';
            image.style.cssText = 'max-width:180px;max-height:140px;border-radius:10px;object-fit:cover';
            preview.append(image);
        } else {
            preview.textContent = file.name;
        }
    });
});
