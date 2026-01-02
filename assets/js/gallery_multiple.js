document.addEventListener('DOMContentLoaded', () => {
    // Memeriksa apakah data proyek ada
    if (typeof projectsData === 'undefined' || !projectsData) {
        console.error('Data proyek tidak ditemukan.');
        const galleryContainer = document.getElementById('gallery');
        if (galleryContainer) {
            galleryContainer.innerHTML = '<p class="text-gray-400 col-span-full text-center">Data galeri tidak tersedia.</p>';
        }
        return;
    }

    const galleryContainer = document.getElementById('gallery');
    const filterContainer = document.getElementById('filter-container');
    
    // Elemen Panel Geser
    const offcanvasContainer = document.getElementById('offcanvas-container');
    const offcanvasPanel = document.getElementById('offcanvas-panel');
    const offcanvasOverlay = document.getElementById('offcanvas-overlay');
    const closePanelBtn = document.getElementById('close-panel-btn');
    const panelTitle = document.getElementById('panel-title');
    const panelMainImage = document.getElementById('panel-main-image');
    const panelThumbnails = document.getElementById('panel-thumbnails');
    const panelDescription = document.getElementById('panel-description');
    const imageTypeIndicator = document.getElementById('image-type-indicator');
    const imageTypeFilter = document.getElementById('image-type-filter');
    const imageCaption = document.getElementById('image-caption');

    // Global variables for current project
    let currentProject = null;
    let currentImageIndex = 0;
    let filteredImages = [];

    // Check required elements
    if (!galleryContainer) {
        console.error('Elemen gallery tidak ditemukan!');
        return;
    }

    // Fungsi untuk merender galeri utama
    const renderGallery = (filter = 'all') => {
        galleryContainer.innerHTML = '';
        
        if (!projectsData || projectsData.length === 0) {
            galleryContainer.innerHTML = '<p class="text-gray-400 col-span-full text-center">Tidak ada proyek untuk ditampilkan.</p>';
            return;
        }

        const filteredProjects = projectsData.filter(p => filter === 'all' || p.category === filter);

        if (filteredProjects.length === 0) {
            galleryContainer.innerHTML = '<p class="text-gray-400 col-span-full text-center">Tidak ada proyek dalam kategori ini.</p>';
            return;
        }

        filteredProjects.forEach(project => {
            const imageSrc = (project.images && project.images.length > 0) ? project.images[0] : 'assets/images/no-image.jpg';
            const imageCount = project.images ? project.images.length : 0;
            
            const card = document.createElement('div');
            card.className = 'group relative rounded-lg overflow-hidden cursor-pointer shadow-lg';
            card.innerHTML = `
                <img src="${imageSrc}" alt="${escapeHtml(project.title)}" class="w-full h-72 object-cover transform group-hover:scale-110 transition-transform duration-500" onerror="this.src='assets/images/no-image.jpg'">
                <div class="card-overlay absolute inset-0 bg-gradient-to-t from-black/80 to-transparent p-6 flex flex-col justify-end">
                    <h3 class="text-2xl font-bold text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">${escapeHtml(project.title)}</h3>
                    ${imageCount > 1 ? `<span class="text-amber-400 text-sm mt-2">${imageCount} gambar</span>` : ''}
                </div>
                ${imageCount > 1 ? `<div class="absolute top-3 right-3 bg-black/70 text-white text-xs px-2 py-1 rounded">${imageCount} foto</div>` : ''}
            `;
            card.addEventListener('click', () => openPanel(project));
            galleryContainer.appendChild(card);
        });
    };

    // Fungsi untuk membuka panel
    const openPanel = (project) => {
        if (!offcanvasContainer || !offcanvasPanel || !offcanvasOverlay) {
            console.error('Elemen panel tidak ditemukan!');
            return;
        }

        currentProject = project;
        currentImageIndex = 0;
        filteredImages = project.imageDetails || [];

        if (panelTitle) panelTitle.textContent = project.title || 'Judul tidak tersedia';
        if (panelDescription) panelDescription.textContent = project.description || project.title || 'Deskripsi tidak tersedia';
        
        // Setup image type filter if multiple images
        setupImageTypeFilter();
        
        // Atur gambar utama dan miniatur
        updatePanelImages();

        // Tampilkan panel
        document.body.classList.add('overflow-hidden');
        offcanvasContainer.classList.remove('pointer-events-none');
        offcanvasOverlay.classList.remove('opacity-0');
        offcanvasPanel.classList.remove('translate-x-full');
    };

    // Setup image type filter
    const setupImageTypeFilter = () => {
        if (!currentProject || !currentProject.imageDetails) return;

        const types = [...new Set(currentProject.imageDetails.map(img => img.type))];
        
        if (types.length > 1) {
            imageTypeFilter.style.display = 'block';
            
            // Setup event listeners for type filter buttons
            const typeButtons = imageTypeFilter.querySelectorAll('.image-type-btn');
            typeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active class from all buttons
                    typeButtons.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    btn.classList.add('active');
                    
                    // Filter images
                    const selectedType = btn.dataset.type;
                    if (selectedType === 'all') {
                        filteredImages = currentProject.imageDetails;
                    } else {
                        filteredImages = currentProject.imageDetails.filter(img => img.type === selectedType);
                    }
                    
                    currentImageIndex = 0;
                    updatePanelImages();
                });
            });

            // Show only type buttons that have images
            typeButtons.forEach(btn => {
                const type = btn.dataset.type;
                if (type === 'all' || types.includes(type)) {
                    btn.style.display = 'inline-block';
                } else {
                    btn.style.display = 'none';
                }
            });
        } else {
            imageTypeFilter.style.display = 'none';
        }
    };

    // Fungsi untuk menutup panel
    const closePanel = () => {
        if (!offcanvasContainer || !offcanvasPanel || !offcanvasOverlay) return;

        document.body.classList.remove('overflow-hidden');
        offcanvasContainer.classList.add('pointer-events-none');
        offcanvasOverlay.classList.add('opacity-0');
        offcanvasPanel.classList.add('translate-x-full');
        
        // Reset filter
        const activeTypeBtn = imageTypeFilter.querySelector('.image-type-btn.active');
        if (activeTypeBtn) activeTypeBtn.classList.remove('active');
        const allBtn = imageTypeFilter.querySelector('[data-type="all"]');
        if (allBtn) allBtn.classList.add('active');
    };

    // Fungsi untuk memperbarui gambar di panel
    const updatePanelImages = () => {
        if (!panelMainImage || !panelThumbnails || !filteredImages.length) return;

        const currentImage = filteredImages[currentImageIndex];
        const imageSrc = currentImage ? currentImage.path : 'assets/images/no-image.jpg';
        
        // Update main image
        panelMainImage.src = imageSrc;
        panelMainImage.onerror = function() {
            this.src = 'assets/images/no-image.jpg';
        };
        
        // Update type indicator
        if (imageTypeIndicator && currentImage) {
            const typeNames = {
                'main': 'Gambar Utama',
                'exterior': 'Eksterior',
                'interior': 'Interior', 
                'detail': 'Detail'
            };
            imageTypeIndicator.textContent = typeNames[currentImage.type] || currentImage.type;
        }
        
        // Update caption
        if (imageCaption && currentImage && currentImage.caption) {
            imageCaption.textContent = currentImage.caption;
            imageCaption.style.display = 'block';
        } else if (imageCaption) {
            imageCaption.style.display = 'none';
        }
        
        // Update thumbnails
        panelThumbnails.innerHTML = '';
        
        if (filteredImages.length > 1) {
            filteredImages.forEach((imageDetail, index) => {
                const thumb = document.createElement('div');
                thumb.className = 'thumbnail-with-type relative';
                
                const img = document.createElement('img');
                img.src = imageDetail.path;
                img.alt = `Miniatur ${index + 1}`;
                img.className = `w-full h-20 object-cover rounded-md cursor-pointer transition-all duration-200 ${index === currentImageIndex ? 'thumbnail-active' : 'opacity-60 hover:opacity-100'}`;
                img.onerror = function() {
                    this.src = 'assets/images/no-image.jpg';
                };
                img.addEventListener('click', () => {
                    currentImageIndex = index;
                    updatePanelImages();
                });
                
                const badge = document.createElement('div');
                badge.className = `thumbnail-type-badge ${imageDetail.type}`;
                badge.textContent = imageDetail.type.charAt(0).toUpperCase();
                
                thumb.appendChild(img);
                thumb.appendChild(badge);
                panelThumbnails.appendChild(thumb);
            });
        }
    };

    // Keyboard navigation for images
    const handleKeyboardNavigation = (e) => {
        if (!currentProject || !filteredImages.length) return;
        
        if (e.key === 'ArrowLeft' && currentImageIndex > 0) {
            currentImageIndex--;
            updatePanelImages();
        } else if (e.key === 'ArrowRight' && currentImageIndex < filteredImages.length - 1) {
            currentImageIndex++;
            updatePanelImages();
        }
    };

    // Event listener untuk filter
    if (filterContainer) {
        filterContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('filter-btn')) {
                // Hapus kelas aktif dari tombol sebelumnya
                const currentActive = filterContainer.querySelector('.filter-btn.active');
                if(currentActive) {
                    currentActive.classList.remove('active', 'bg-amber-400', 'text-gray-900');
                    currentActive.classList.add('bg-gray-700', 'text-gray-200');
                }
                
                // Tambahkan kelas aktif ke tombol yang diklik
                e.target.classList.add('active', 'bg-amber-400', 'text-gray-900');
                e.target.classList.remove('bg-gray-700', 'text-gray-200');

                renderGallery(e.target.dataset.filter);
            }
        });
    }

    // Event listeners
    if(closePanelBtn) closePanelBtn.addEventListener('click', closePanel);
    if(offcanvasOverlay) offcanvasOverlay.addEventListener('click', closePanel);

    // Keyboard event listeners
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePanel();
        } else {
            handleKeyboardNavigation(e);
        }
    });

    // Fungsi helper untuk escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Inisialisasi galeri saat halaman dimuat
    renderGallery();
});