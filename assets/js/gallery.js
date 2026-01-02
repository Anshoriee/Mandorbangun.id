document.addEventListener('DOMContentLoaded', () => {
    // Pastikan semua data dan library tersedia
    if (typeof projectsData === 'undefined' || typeof categoriesData === 'undefined' || typeof gsap === 'undefined' || typeof Flip === 'undefined') {
        console.error('Data atau library animasi (GSAP, Flip.js) tidak ditemukan.');
        return;
    }

    gsap.registerPlugin(Flip); // Daftarkan plugin Flip

    const galleryContainer = document.getElementById('gallery');
    const filterContainer = document.getElementById('filter-container');
    
    // Elemen Panel Geser (tidak berubah)
    const offcanvasContainer = document.getElementById('offcanvas-container');
    const offcanvasPanel = document.getElementById('offcanvas-panel');
    const offcanvasOverlay = document.getElementById('offcanvas-overlay');
    const closePanelBtn = document.getElementById('close-panel-btn');
    const panelTitle = document.getElementById('panel-title');
    const panelMainImage = document.getElementById('panel-main-image');
    const panelThumbnails = document.getElementById('panel-thumbnails');
    const panelDescription = document.getElementById('panel-description');

    const createFilterButtons = () => {
        let buttonsHTML = `<button class="filter-btn active" data-filter="all">Semua</button>`;
        categoriesData.forEach(category => {
            const category_slug = category.name.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-');
            buttonsHTML += `<button class="filter-btn" data-filter="${escapeHtml(category_slug)}">${escapeHtml(category.name)}</button>`;
        });
        filterContainer.innerHTML = buttonsHTML;
    };
    
    // FUNGSI RENDER DENGAN ANIMASI FLIP YANG SANGAT HALUS
    const renderGallery = (filter = 'all') => {
        const items = Array.from(galleryContainer.children);
        
        // 1. Dapatkan state awal (posisi dan ukuran semua item)
        const state = Flip.getState(items);

        // 2. Lakukan perubahan layout (sembunyikan/tampilkan item)
        items.forEach(item => {
            const shouldShow = (filter === 'all' || item.dataset.category === filter);
            item.style.display = shouldShow ? 'block' : 'none';
        });

        // 3. Animasikan dari state awal ke state akhir
        Flip.from(state, {
            duration: 0.7, // Durasi animasi
            scale: true, // Animasikan juga ukuran jika berubah
            ease: "power1.inOut", // Kurva animasi yang halus
            stagger: 0.08, // Jeda antar item untuk efek riak
            // Efek fade-in untuk item yang baru muncul
            onEnter: elements => gsap.fromTo(elements, { opacity: 0, scale: 0.9 }, { opacity: 1, scale: 1, duration: 0.5 }),
            // Efek fade-out untuk item yang hilang
            onLeave: elements => gsap.to(elements, { opacity: 0, scale: 0.9, duration: 0.5 })
        });
    };
    
    const buildInitialGallery = () => {
        projectsData.forEach(project => {
            const imageSrc = (project.images && project.images.length > 0) ? project.images[0].path : 'assets/images/no-image.jpg';
            const card = document.createElement('div');
            card.className = 'group relative rounded-lg overflow-hidden cursor-pointer shadow-lg portfolio-item';
            card.dataset.category = project.category;
            card.innerHTML = `
                <img src="${imageSrc}" alt="${escapeHtml(project.title)}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" onerror="this.src='assets/images/no-image.jpg'">
                <div class="card-overlay absolute inset-0 bg-gradient-to-t from-black/80 to-transparent p-6 flex flex-col justify-end">
                    <h3 class="text-2xl font-bold text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">${escapeHtml(project.title)}</h3>
                </div>
            `;
            card.addEventListener('click', () => openPanel(project));
            galleryContainer.appendChild(card);
        });
    };

    const openPanel = (project) => {
        panelTitle.textContent = project.title || 'Judul tidak tersedia';
        panelDescription.textContent = project.description || 'Deskripsi tidak tersedia';
        updatePanelImages(project, 0);

        document.body.classList.add('overflow-hidden');
        offcanvasContainer.classList.add('active');
    };

    const closePanel = () => {
        document.body.classList.remove('overflow-hidden');
        offcanvasContainer.classList.remove('active');
    };

    const updatePanelImages = (project, activeIndex) => {
        const images = project.images || [];
        if (images.length === 0) {
            panelMainImage.src = 'assets/images/no-image.jpg';
            panelThumbnails.innerHTML = '';
            return;
        }

        const activeImage = images[activeIndex];
        panelMainImage.src = activeImage.path || 'assets/images/no-image.jpg';
        
        panelThumbnails.innerHTML = '';
        if (images.length > 0) {
            images.forEach((image, index) => {
                const thumbContainer = document.createElement('div');
                thumbContainer.className = `thumbnail-item ${index === activeIndex ? 'thumbnail-active' : ''}`;

                const thumbImg = document.createElement('img');
                thumbImg.src = image.path;
                thumbImg.alt = `Miniatur ${index + 1}`;
                thumbImg.onerror = () => { thumbImg.src = 'assets/images/no-image.jpg'; };

                const thumbCaption = document.createElement('p');
                thumbCaption.className = 'thumbnail-caption';
                thumbCaption.textContent = image.caption || '';

                thumbContainer.appendChild(thumbImg);
                if (image.caption) {
                    thumbContainer.appendChild(thumbCaption);
                }

                thumbContainer.addEventListener('click', () => updatePanelImages(project, index));
                panelThumbnails.appendChild(thumbContainer);
            });
        }
    };

    filterContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('filter-btn') && !e.target.classList.contains('active')) {
            filterContainer.querySelector('.filter-btn.active')?.classList.remove('active');
            e.target.classList.add('active');
            renderGallery(e.target.dataset.filter);
        }
    });

    closePanelBtn.addEventListener('click', closePanel);
    offcanvasOverlay.addEventListener('click', closePanel);
    document.addEventListener('keydown', (e) => { 
        if (e.key === 'Escape' && offcanvasContainer.classList.contains('active')) {
            closePanel();
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    // INISIALISASI
    createFilterButtons();
    buildInitialGallery();
});
