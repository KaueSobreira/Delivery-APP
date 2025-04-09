document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarCollapse');
    const content = document.getElementById('content');
    const navLinks = document.querySelectorAll('#sidebar .nav-link');
    const categoriaCards = document.querySelectorAll('.categoria-card');
    const PRODUTOS_LIMITE = 2;

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        content.classList.toggle('active');
        
        navLinks.forEach(link => {
            link.classList.toggle('collapsed');
        });
    }

    function deveExpandir(collapse) {
        const produtos = collapse.querySelectorAll('.col-md-6');
        return produtos.length > PRODUTOS_LIMITE;
    }
    function resetCategorias() {
        document.querySelectorAll('.categoria-wrapper').forEach(wrapper => {
            wrapper.classList.remove('expanded');
        });
        categoriaCards.forEach(card => {
            card.classList.remove('active');
        });
    }
    categoriaCards.forEach(card => {
        if (!card.parentElement.classList.contains('categoria-wrapper')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'categoria-wrapper';
            card.parentElement.insertBefore(wrapper, card);
            wrapper.appendChild(card);
            if (card.nextElementSibling) {
                wrapper.appendChild(card.nextElementSibling);
            }
        }

        card.addEventListener('click', function() {
            const wrapper = this.closest('.categoria-wrapper');
            const collapse = document.querySelector(this.getAttribute('data-bs-target'));

            categoriaCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            collapse.addEventListener('shown.bs.collapse', function() {
                if (deveExpandir(collapse) && window.innerWidth >= 992) {
                    wrapper.classList.add('expanded');
                }
            }, { once: true });

            collapse.addEventListener('hide.bs.collapse', function() {
                wrapper.classList.remove('expanded');
            });

            categoriaCards.forEach(c => {
                if (c !== this) {
                    const target = c.getAttribute('data-bs-target');
                    const otherCollapse = document.querySelector(target);
                    if (otherCollapse && otherCollapse.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(otherCollapse).hide();
                        otherCollapse.closest('.categoria-wrapper').classList.remove('expanded');
                    }
                }
            });
        });
    });

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        });
    });

    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target) &&
            sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
            resetCategorias();
        }
    });
    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // Menu mobile toggle
    const btnMobile = document.querySelector('.btnMobile');
    const navItems = document.querySelector('.items');
    
    if (btnMobile) {
        btnMobile.addEventListener('click', function() {
            navItems.classList.toggle('active');
        });
    }
    
    // Fechar menu ao clicar em um link
    const navLinksMobile = document.querySelectorAll('.items a');
    navLinksMobile.forEach(link => {
        link.addEventListener('click', function() {
            navItems.classList.remove('active');
        });
    });
    
    // Adicionar ícones através do Iconify (se disponível)
    if (window.Iconify) {
        Iconify.scan();
    }
    
    // Rolagem suave para as âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });
});
