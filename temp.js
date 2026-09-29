


    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              gold: '#D4AF37',
              'gold-hover': '#B8960C',
              light: '#FFFBEB',
              dark: '#0A0A0A',
              gray: '#64748B',
              border: '#E2E8F0',
              accent: '#F5D060'
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif'],
            condensed: ['"Barlow Condensed"', 'sans-serif'],
          }
        }
      }
    }
  

    function toggleMemberPanel() {
      const panel = document.getElementById('member-panel');
      const isOpen = !panel.classList.contains('translate-x-full');
      isOpen ? closeMemberPanel() : openMemberPanel();
    }
    function openMemberPanel() {
      const panel = document.getElementById('member-panel');
      const overlay = document.getElementById('member-overlay');
      const icon = document.getElementById('member-tab-icon');
      panel.classList.remove('translate-x-full');
      overlay.classList.remove('opacity-0', 'pointer-events-none');
      icon.classList.add('rotate-0');
      icon.classList.remove('rotate-180');
      document.body.style.overflow = 'hidden';
    }
    function closeMemberPanel() {
      const panel = document.getElementById('member-panel');
      const overlay = document.getElementById('member-overlay');
      const icon = document.getElementById('member-tab-icon');
      panel.classList.add('translate-x-full');
      overlay.classList.add('opacity-0', 'pointer-events-none');
      icon.classList.remove('rotate-0');
      icon.classList.add('rotate-180');
      document.body.style.overflow = '';
    }
    function handleMemberSubmit(e) {
      e.preventDefault();
      document.getElementById('member-form').classList.add('hidden');
      document.getElementById('member-success').classList.remove('hidden');
      document.getElementById('member-success').classList.add('flex');
    }
    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMemberPanel();
    });
  


        (function () {
          const branches = [
            { name: 'Hercules Fitness — Batu Ampar', lat: 1.166568987207872, lng: 104.0090237477667, address: 'Jl. Engku Putri, Batu Ampar, Batam', hours: 'Senin–Jumat 07.00–24.00 | Sabtu–Minggu 07.00–23.00', region: 'batam', link: '<?= \yii\helpers\Url::to(['site/location-detail', 'id' => 'batu-ampar']) ?>' },
            { name: 'Hercules Fitness — Batu Besar', lat: 1.1402346807509265, lng: 104.11291618410677, address: 'Jl. Barelang, Batu Besar, Batam', hours: 'Senin–Jumat 07.00–24.00 | Sabtu–Minggu 07.00–23.00', region: 'batam', link: '<?= \yii\helpers\Url::to(['site/location-detail', 'id' => 'batu-besar']) ?>' },
            { name: 'Hercules Fitness — Canggu', lat: -8.635666883372355, lng: 115.14250261072259, address: 'Jl. Raya Canggu, Bali', hours: 'Senin–Jumat 06.00–24.00 | Sabtu–Minggu 06.00–22.00', region: 'bali', link: '<?= \yii\helpers\Url::to(['site/location-detail', 'id' => 'canggu']) ?>' },
            { name: 'Hercules Fitness — Kuta', lat: -8.725696688872542, lng: 115.17655350887125, address: 'Jl. Raya Kuta No.20, Badung, Bali', hours: 'Senin–Jumat 06.00–24.00 | Sabtu–Minggu 06.00–22.00', region: 'bali', link: '<?= \yii\helpers\Url::to(['site/location-detail', 'id' => 'kuta']) ?>' }
          ];

          const map = L.map('hercules-map', {
            center: [1.1533, 104.0609], /* Midpoint */
            zoom: 12,
            zoomControl: false,
            scrollWheelZoom: false
          });

          L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
          }).addTo(map);

          L.control.zoom({ position: 'bottomright' }).addTo(map);

          const goldIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="width:36px;height:36px;background:#D4AF37;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(212,175,55,0.5),0 4px 12px rgba(0,0,0,0.4);border:3px solid #fff;overflow:hidden;"><img src="/img/hercules.jpeg" style="width:100%;height:100%;object-fit:cover;" /></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -22]
          });

          const markers = branches.map((b, i) => {
            const marker = L.marker([b.lat, b.lng], { icon: goldIcon }).addTo(map);
            marker.bindPopup(`
          <div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:200px">
            <h4 style="font-weight:800;font-size:13px;margin:0 0 4px 0;color:#0a0a0a">${b.name}</h4>
            <p style="font-size:11px;color:#64748B;margin:0 0 4px 0">${b.address}</p>
            <p style="font-size:10px;color:#D4AF37;font-weight:600;margin:0 0 10px 0">🕐 ${b.hours}</p>
            <a href="${b.link}" style="display:block;text-align:center;background:#121212;color:#D4A017;font-size:11px;font-weight:700;text-decoration:none;padding:6px 0;border-radius:6px;text-transform:uppercase;letter-spacing:1px;transition:background 0.2s;">Lihat Detail Cabang</a>
          </div>
        `, { className: 'hercules-popup' });
            return marker;
          });

          markers[0].openPopup();

          window.flyToBranch = function (index) {
            const b = branches[index];
            map.flyTo([b.lat, b.lng], 15, { duration: 1.2 });
            markers[index].openPopup();

            document.querySelectorAll('#branch-buttons-container .branch-btn').forEach((btn) => {
              const branchIndex = parseInt(btn.id.replace('branch-btn-', ''));
              const iconContainer = btn.querySelector('.w-8');
              const icon = btn.querySelector('i');
              if (branchIndex === index) {
                btn.classList.add('border-brand-gold', 'bg-brand-gold/10');
                btn.classList.remove('border-white/10', 'hover:border-white/30');
                if (iconContainer) {
                  iconContainer.classList.add('bg-brand-gold/20');
                  iconContainer.classList.remove('bg-white/10');
                }
                if (icon) {
                  icon.classList.add('text-brand-gold');
                  icon.classList.remove('text-slate-400');
                }
              } else {
                btn.classList.remove('border-brand-gold', 'bg-brand-gold/10');
                btn.classList.add('border-white/10', 'hover:border-white/30');
                if (iconContainer) {
                  iconContainer.classList.remove('bg-brand-gold/20');
                  iconContainer.classList.add('bg-white/10');
                }
                if (icon) {
                  icon.classList.remove('text-brand-gold');
                  icon.classList.add('text-slate-400');
                }
              }
            });
          };

          window.switchRegion = function (region) {
            document.querySelectorAll('.region-btn').forEach(btn => {
              if (btn.id === 'region-btn-' + region) {
                btn.classList.add('border-brand-gold', 'text-brand-gold');
                btn.classList.remove('border-transparent', 'text-slate-400', 'hover:text-white');
              } else {
                btn.classList.remove('border-brand-gold', 'text-brand-gold');
                btn.classList.add('border-transparent', 'text-slate-400', 'hover:text-white');
              }
            });

            let firstBranchIndex = -1;
            document.querySelectorAll('#branch-buttons-container .branch-btn').forEach(btn => {
              if (btn.dataset.region === region) {
                btn.classList.add('flex');
                btn.classList.remove('hidden');
                if (firstBranchIndex === -1) {
                  firstBranchIndex = parseInt(btn.id.replace('branch-btn-', ''));
                }
              } else {
                btn.classList.remove('flex');
                btn.classList.add('hidden');
              }
            });

            if (firstBranchIndex !== -1) {
              flyToBranch(firstBranchIndex);
            }
          };

          // Auto-scroll gallery
          const container = document.getElementById('gallery-scroll-container');
          const strip = document.querySelector('.gallery-scroll-strip');
          if (container && strip) {
            let paused = false;
            const speed = 0.5;

            function animateGallery() {
              if (!paused) {
                container.scrollTop += speed;
                if (container.scrollTop >= strip.scrollHeight / 2) {
                  container.scrollTop = 0;
                }
              }
              requestAnimationFrame(animateGallery);
            }
            animateGallery();

            container.addEventListener('mouseenter', () => { paused = true; });
            container.addEventListener('mouseleave', () => { paused = false; });

            // Ensure manual scroll wraps around smoothly
            container.addEventListener('scroll', () => {
              if (container.scrollTop >= strip.scrollHeight / 2) {
                container.scrollTop = 0;
              }
            });
          }
        })();
      




    gsap.registerPlugin(ScrollTrigger, Draggable);

    // ===== HERO ENTRANCE ANIMATION =====
    const heroTl = gsap.timeline({ defaults: { ease: "power3.out" } });

    // Navbar fade in from top
    heroTl.fromTo("#main-header",
      { y: -40, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.8 }
    );


    // Hero heading
    heroTl.fromTo("#hero-title",
      { y: 60, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.9 },
      "-=0.4"
    );

    // Hero paragraph
    heroTl.fromTo("#hero-desc",
      { y: 40, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.7 },
      "-=0.5"
    );

    // Card selector at bottom (staggered)
    heroTl.fromTo(".hero-select-card",
      { y: 40, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.5, stagger: 0.1, ease: "power2.out" },
      "-=0.3"
    );

    // ===== CONTROL HINTS BLOCK REVEAL ANIMATION =====
    heroTl.add("hintsReveal", "-=0.2");

    // 1. Animate the hint buttons (Horizontal Wireframe Stretch)
    const hintBtns = document.querySelectorAll(".hint-btn");
    hintBtns.forEach((btn, index) => {
      // Delay each button row (note: arrow keys have 2 buttons, we can stagger them slightly)
      const rowDelay = Math.floor(index / 1.5) * 0.15; // approximate grouping for the 3 rows

      heroTl.fromTo(btn,
        { scaleX: 0, transformOrigin: "center" },
        { scaleX: 1, duration: 0.4, ease: "power3.out" },
        `hintsReveal+=${rowDelay}`
      );

      const content = btn.querySelector(".hint-btn-content");
      if (content) {
        heroTl.to(content, { opacity: 1, duration: 0.2 }, `hintsReveal+=${rowDelay + 0.3}`);
      }
    });

    // 2. Animate the hint text (Block Reveal & Stagger)
    const hintTexts = document.querySelectorAll(".block-reveal-text");
    hintTexts.forEach((el, index) => {
      const text = el.innerText;
      el.innerHTML = ""; // Clear existing text

      // Create wrapper
      const wrapper = document.createElement("span");
      wrapper.className = "relative inline-block overflow-hidden align-middle";

      // Create sliding block
      const block = document.createElement("span");
      block.className = "absolute inset-0 bg-white z-10 origin-left scale-x-0 pointer-events-none";
      wrapper.appendChild(block);

      // Create text container
      const textContainer = document.createElement("span");
      textContainer.className = "opacity-0 flex";

      // Split text into spans for staggered typing effect
      text.split("").forEach(char => {
        const charSpan = document.createElement("span");
        charSpan.innerText = char === " " ? "\u00A0" : char;
        textContainer.appendChild(charSpan);
      });

      wrapper.appendChild(textContainer);
      el.appendChild(wrapper);

      // Add to hero timeline with stagger delay based on index
      // Synchronize with the button animations (0.2 delay to start slightly after button appears)
      const delay = (index * 0.15) + 0.2;

      heroTl.to(block, { scaleX: 1, duration: 0.35, ease: "power3.inOut" }, `hintsReveal+=${delay}`)
        .set(textContainer, { opacity: 1 }, `hintsReveal+=${delay + 0.35}`)
        .to(block, { scaleX: 0, duration: 0.35, ease: "power3.inOut", transformOrigin: "right" }, `hintsReveal+=${delay + 0.35}`)
        .from(textContainer.children, { opacity: 0, x: -3, duration: 0.05, stagger: 0.015 }, `hintsReveal+=${delay + 0.35}`);
    });

    // ===== NAVBAR SCROLL EFFECT (Active only past Hero section) =====
    const headerElem = document.getElementById("main-header");
    let isHideHeaderForced = false;

    ScrollTrigger.create({
      start: "top -80px", // Activates when scrolled 80px down
      end: "max", // Keeps active until the very bottom of the page
      onUpdate: (self) => {
        if (isHideHeaderForced) return; // Skip if in Services section
        if (self.direction === 1) { // Scrolling down
          gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" });
        } else { // Scrolling up
          gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" });
        }
      },
      onLeaveBack: () => {
        if (isHideHeaderForced) return;
        // Ensure header is fully visible when scrolling back to top
        gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" });
      }
    });

    // ===== BERGABUNG BUTTON VISIBILITY =====
    ScrollTrigger.create({
      trigger: "[data-purpose='hero-section']",
      start: "bottom 70%", // Triggers when the bottom of the hero section reaches 70% from the top of viewport
      onEnter: () => document.getElementById("member-tab-btn").classList.remove("translate-x-full"),
      onLeaveBack: () => document.getElementById("member-tab-btn").classList.add("translate-x-full"),
    });

    // ===== HELPER: Create scroll-triggered fade-in =====
    function animateOnScroll(selector, fromVars, triggerVars = {}) {
      gsap.from(selector, {
        scrollTrigger: {
          trigger: selector,
          start: triggerVars.start || "top 85%",
          toggleActions: "play none none none",
          ...triggerVars,
        },
        duration: 0.8,
        ease: "power2.out",
        ...fromVars,
      });
    }

    // ===== MISSION / PHILOSOPHY SECTION =====
    animateOnScroll("[data-purpose='mission-philosophy'] h2", { y: 50, opacity: 0, duration: 0.9 });
    animateOnScroll("[data-purpose='mission-philosophy'] .max-w-md", { y: 40, opacity: 0, duration: 0.7 }, { start: "top 80%" });

    // Workout category cards - stagger
    gsap.from("[data-purpose='mission-philosophy'] .grid > div", {
      scrollTrigger: {
        trigger: "[data-purpose='mission-philosophy']",
        start: "top 95%",
        toggleActions: "play none none none",
      },
      y: 50,
      opacity: 0,
      duration: 0.7,
      stagger: 0.15,
      ease: "power2.out",
    });



    // ===== WORKOUT SHOWCASE – DIAGONAL SPLIT SCREEN =====
    (function () {
      const diagSection = document.getElementById("layanan");
      if (!diagSection) return;

      // Create a master timeline pinned to the section
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: diagSection,
          start: "top top",
          end: "+=4000",
          scrub: 1,
          pin: true,
          anticipatePin: 1,
          onEnter: () => { isHideHeaderForced = true; gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" }); },
          onLeave: () => { isHideHeaderForced = false; },
          onEnterBack: () => { isHideHeaderForced = true; gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" }); },
          onLeaveBack: () => { isHideHeaderForced = false; gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" }); }
        }
      });

      // Define proxy objects with initial numeric values for the polygons
      const p1 = { t: 30, b: 20 };
      const p2 = { tl: 31, tr: 55, br: 45, bl: 21 };
      const p3 = { tl: 56, tr: 80, br: 70, bl: 46 };
      const p4 = { tl: 81, bl: 71 };

      const panel1 = document.getElementById("diag-panel-1");
      const panel2 = document.getElementById("diag-panel-2");
      const panel3 = document.getElementById("diag-panel-3");
      const panel4 = document.getElementById("diag-panel-4");

      // Unified onUpdate to render all panels each frame
      const renderPanels = () => {
        panel1.style.clipPath = `polygon(0% 0%, ${p1.t}% 0%, ${p1.b}% 100%, 0% 100%)`;
        panel1.style.webkitClipPath = `polygon(0% 0%, ${p1.t}% 0%, ${p1.b}% 100%, 0% 100%)`;

        panel2.style.clipPath = `polygon(${p2.tl}% 0%, ${p2.tr}% 0%, ${p2.br}% 100%, ${p2.bl}% 100%)`;
        panel2.style.webkitClipPath = `polygon(${p2.tl}% 0%, ${p2.tr}% 0%, ${p2.br}% 100%, ${p2.bl}% 100%)`;

        panel3.style.clipPath = `polygon(${p3.tl}% 0%, ${p3.tr}% 0%, ${p3.br}% 100%, ${p3.bl}% 100%)`;
        panel3.style.webkitClipPath = `polygon(${p3.tl}% 0%, ${p3.tr}% 0%, ${p3.br}% 100%, ${p3.bl}% 100%)`;

        panel4.style.clipPath = `polygon(${p4.tl}% 0%, 100% 0%, 100% 100%, ${p4.bl}% 100%)`;
        panel4.style.webkitClipPath = `polygon(${p4.tl}% 0%, 100% 0%, 100% 100%, ${p4.bl}% 100%)`;
      };

      // 1. Intro fades out
      tl.to("#services-intro", { opacity: 0, y: -30, duration: 0.5 });

      // Accordion State 1: Panel 1 Active
      // P1 pushes P2, P3, P4 into thin strips on the right
      tl.to(p1, { t: 76, b: 66, duration: 1, onUpdate: renderPanels }, "-=0.2")
        .to(p2, { tl: 76, tr: 84, br: 74, bl: 66, duration: 1 }, "<")
        .to(p3, { tl: 84, tr: 92, br: 82, bl: 74, duration: 1 }, "<")
        .to(p4, { tl: 92, bl: 82, duration: 1 }, "<")
        .to("#diag-overlay-1", { opacity: 0, duration: 1 }, "<")
        .to("#diag-content-1", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

      tl.to({}, { duration: 0.3 }); // Pause

      // Accordion State 2: Panel 2 Active
      // P2 left edge sweeps to 0, completely covering P1 on the left side
      tl.to(p2, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
        .to("#diag-overlay-1", { opacity: 1, duration: 1 }, "<")
        .to("#diag-content-1", { opacity: 0, y: 10, duration: 0.3 }, "<")
        .to("#diag-overlay-2", { opacity: 0, duration: 1 }, "<")
        .to("#diag-content-2", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

      tl.to({}, { duration: 0.3 }); // Pause

      // Accordion State 3: Panel 3 Active
      // P3 left edge sweeps to 0, completely covering P2 and P1
      tl.to(p3, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
        .to("#diag-overlay-2", { opacity: 1, duration: 1 }, "<")
        .to("#diag-content-2", { opacity: 0, y: 10, duration: 0.3 }, "<")
        .to("#diag-overlay-3", { opacity: 0, duration: 1 }, "<")
        .to("#diag-content-3", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

      tl.to({}, { duration: 0.3 }); // Pause

      // Accordion State 4: Panel 4 Active
      // P4 left edge sweeps to 0, completely covering everything on the left
      tl.to(p4, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
        .to("#diag-overlay-3", { opacity: 1, duration: 1 }, "<")
        .to("#diag-content-3", { opacity: 0, y: 10, duration: 0.3 }, "<")
        .to("#diag-overlay-4", { opacity: 0, duration: 1 }, "<")
        .to("#diag-content-4", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

    })();

    // ===== WHY HERCULES — STICKY STACKING CARDS =====
    // Header fade in
    animateOnScroll("[data-purpose='why-hercules-fitness-centre'] .max-w-7xl > div", { y: 50, opacity: 0, duration: 0.9 });

    // Scale-down effect: each card shrinks as the next card covers it
    const stackCards = gsap.utils.toArray(".why-stack-card");
    const stackWrappers = gsap.utils.toArray(".why-stack-wrapper");
    stackCards.forEach((card, i) => {
      // Don't apply scale-out to the last card
      if (i < stackCards.length - 1) {
        gsap.to(card, {
          scale: 0.92,
          opacity: 0.6,
          borderRadius: "2.5rem",
          ease: "none",
          scrollTrigger: {
            trigger: stackWrappers[i + 1],
            start: "top 80%",
            end: "top top",
            scrub: true,
          }
        });
      }
    });


    // ===== PRICING SECTION =====

    animateOnScroll("[data-purpose='pricing-membership'] .text-center", { y: 40, opacity: 0, duration: 0.8 });

    gsap.from("[data-purpose='pricing-membership'] .grid > div", {
      scrollTrigger: {
        trigger: "[data-purpose='pricing-membership'] .grid",
        start: "top 80%",
        toggleActions: "play none none none",
      },
      y: 70,
      opacity: 0,
      scale: 0.92,
      duration: 0.7,
      stagger: 0.18,
      ease: "back.out(1.4)",
    });

    // ===== NEWSLETTER SECTION =====
    animateOnScroll("[data-purpose='newsletter-exclusive-perks'] .rounded-3xl", {
      y: 50,
      opacity: 0,
      scale: 0.97,
      duration: 0.9,
    });

    // ===== BOTTOM CTA =====
    animateOnScroll("[data-purpose='final-call-to-action'] h2", {
      y: 40,
      opacity: 0,
      duration: 0.8,
    });
    animateOnScroll("[data-purpose='final-call-to-action'] a", {
      y: 20,
      opacity: 0,
      scale: 0.9,
      duration: 0.6,
    }, { start: "top 85%" });

    // ===== FOOTER =====
    gsap.from("footer .grid > div", {
      scrollTrigger: {
        trigger: "footer .grid",
        start: "top 90%",
        toggleActions: "play none none none",
      },
      y: 30,
      opacity: 0,
      duration: 0.5,
      stagger: 0.1,
      ease: "power2.out",
    });

    // ===== PS CONSOLE HERO CARD SELECTOR =====
    const heroCards = document.querySelectorAll(".hero-select-card");
    const heroBgImgs = document.querySelectorAll(".hero-bg-img");
    const heroTitle = document.getElementById("hero-title");
    const heroDesc = document.getElementById("hero-desc");
    const heroLabels = document.querySelectorAll(".hero-card-label");

    const heroData = [
      {
        title: 'Ubah <span class="italic-serif font-normal text-brand-accent">Tubuhmu</span>',
        desc: 'Ambil kendali kesehatan Anda dengan program kebugaran yang dirancang khusus untuk mencapai bentuk tubuh ideal dan gaya hidup sehat Anda.'
      },
      {
        title: 'Lampaui <span class="italic-serif font-normal text-brand-accent">Batasmu</span>',
        desc: 'Bergabunglah dengan komunitas kebugaran kami, dan temukan seberapa jauh batas kemampuan fisik Anda dapat dilampaui setiap harinya.'
      },
      {
        title: 'Tingkatkan <span class="italic-serif font-normal text-brand-accent">Rutinitasmu</span>',
        desc: 'Temukan keseimbangan sempurna antara sesi latihan beban intensif dan teknik pemulihan yang tepat untuk hasil kebugaran yang maksimal.'
      },
      {
        title: 'Kuasai <span class="italic-serif font-normal text-brand-accent">Setiap Gerakan</span>',
        desc: 'Tingkatkan daya tahan kardiovaskular dan bakar lemak dengan sesi battle rope yang dinamis dan berintensitas tinggi secara menyeluruh.'
      },
      {
        title: 'Bertarung Penuh <span class="italic-serif font-normal text-brand-accent">Semangat</span>',
        desc: 'Lepaskan stres dan tingkatkan ketangkasan Anda melalui perpaduan teknik tinju dan tendangan yang menantang namun sangat menyenangkan.'
      }
    ];

    let activeHeroCard = 0;
    let heroAutoSlide;

    function setActiveHeroCard(index) {
      activeHeroCard = index;

      // Reset Auto Slide
      clearInterval(heroAutoSlide);
      heroAutoSlide = setInterval(() => {
        setActiveHeroCard((activeHeroCard + 1) % heroData.length);
      }, 5000);

      // Fade backgrounds
      heroBgImgs.forEach((img, i) => {
        img.style.opacity = i === index ? "1" : "0";
      });

      // Resize cards + glow + labels + left-aligned slide effect
      heroCards.forEach((card, i) => {
        const label = heroLabels[i];
        const wrapper = card.parentElement;

        let diff = i - index;
        if (diff < -1) diff += heroCards.length;
        if (diff > 3) diff -= heroCards.length;

        // Calculate positions for left-aligned track
        let isMobile = window.innerWidth < 640;
        let activeW = isMobile ? 180 : 200;
        let inactiveW = isMobile ? 100 : 120;
        let gap = isMobile ? 12 : 16;

        let x = 0;
        if (diff === -1) {
          x = -(inactiveW + gap);
        } else if (diff === 0) {
          x = 0;
        } else {
          x = activeW + gap + (diff - 1) * (inactiveW + gap);
        }

        const prevDiff = wrapper.dataset.diff !== undefined ? parseInt(wrapper.dataset.diff) : diff;
        wrapper.dataset.diff = diff;

        // Disable transition when jumping from end to beginning
        if (Math.abs(prevDiff - diff) > 2) {
          wrapper.style.transition = "none";
          wrapper.style.transform = `translateX(${x}px)`;
          void wrapper.offsetWidth; // Force reflow
        }

        // Apply to wrapper
        wrapper.style.transition = ""; // Restore CSS transition
        wrapper.style.transform = `translateX(${x}px)`;
        wrapper.style.width = diff === 0 ? `${activeW}px` : `${inactiveW}px`;
        wrapper.style.zIndex = 50 - Math.abs(diff);
        wrapper.style.opacity = diff === -1 ? "0" : "1";

        if (diff === 0) {
          // Active: big card with glow
          card.style.height = isMobile ? "220px" : "240px";
          card.classList.remove("border-white/20");
          card.classList.add("border-brand-gold", "ring-2", "ring-brand-gold/50", "pointer-events-none");
          card.style.boxShadow = "0 0 25px rgba(212,175,55,0.4)";
          if (label) {
            label.classList.remove("text-white/50", "text-[10px]", "font-medium");
            label.classList.add("text-white/90", "text-xs", "font-semibold");
          }
        } else {
          // Inactive: small thumbnail, no glow
          card.style.height = isMobile ? "130px" : "150px";
          card.classList.add("border-white/20");
          card.classList.remove("border-brand-gold", "ring-2", "ring-brand-gold/50", "pointer-events-none");
          card.style.boxShadow = "none";
          if (label) {
            label.classList.add("text-white/50", "text-[10px]", "font-medium");
            label.classList.remove("text-white/90", "text-xs", "font-semibold");
          }
        }
      });

      // Animate text
      if (heroTitle && heroDesc) {
        heroTitle.style.opacity = "0";
        heroDesc.style.opacity = "0";

        setTimeout(() => {
          heroTitle.innerHTML = heroData[index].title;
          heroDesc.textContent = heroData[index].desc;
          heroTitle.style.opacity = "1";
          heroDesc.style.opacity = "1";
        }, 350);
      }
    }

    // Bind click
    heroCards.forEach((card) => {
      card.addEventListener("click", () => {
        const idx = parseInt(card.dataset.index);
        if (idx !== activeHeroCard) {
          setActiveHeroCard(idx);
        }
      });
    });

    // Hero Arrow Navigation
    const heroPrev = document.getElementById("hero-prev");
    const heroNext = document.getElementById("hero-next");
    if (heroPrev) heroPrev.addEventListener("click", () => setActiveHeroCard((activeHeroCard - 1 + heroData.length) % heroData.length));
    if (heroNext) heroNext.addEventListener("click", () => setActiveHeroCard((activeHeroCard + 1) % heroData.length));

    // Initialize first card immediately
    setActiveHeroCard(0);

    // Keyboard navigation
    document.addEventListener("keydown", (e) => {
      const heroSection = document.getElementById("beranda");
      const rect = heroSection.getBoundingClientRect();
      if (rect.top > window.innerHeight || rect.bottom < 0) return;

      if (e.key === "ArrowRight") {
        setActiveHeroCard((activeHeroCard + 1) % heroData.length);
      } else if (e.key === "ArrowLeft") {
        setActiveHeroCard((activeHeroCard - 1 + heroData.length) % heroData.length);
      }
    });

    // FAQ Accordion Logic
    window.toggleFaq = function (btn) {
      const answer = btn.nextElementSibling;
      const icon = btn.querySelector('.faq-icon');

      // Check if currently open
      const isOpen = answer.style.gridTemplateRows === '1fr';

      // Close all other FAQs (accordion style)
      document.querySelectorAll('.faq-answer').forEach(el => {
        el.style.gridTemplateRows = '0fr';
      });
      document.querySelectorAll('.faq-icon').forEach(el => {
        el.style.transform = 'rotate(0deg)';
        el.classList.remove('text-brand-gold');
      });
      document.querySelectorAll('.faq-item').forEach(el => {
        el.classList.remove('border-brand-gold/50');
      });

      if (!isOpen) {
        answer.style.gridTemplateRows = '1fr';
        icon.style.transform = 'rotate(135deg)';
        icon.classList.add('text-brand-gold');
        btn.parentElement.classList.add('border-brand-gold/50');
      }
    };

    // FAQ Spotlight Effect
    const faqSection = document.getElementById("faq");

    if (faqSection) {
      faqSection.addEventListener("mousemove", (e) => {
        const rect = faqSection.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        faqSection.style.setProperty("--mouse-x", `${x}px`);
        faqSection.style.setProperty("--mouse-y", `${y}px`);
      });
    }

    // Hold Enter to Start Journey Logic
    const heroCtaFill = document.getElementById("hero-cta-fill");
    const heroCtaBtn = document.getElementById("hero-cta-btn");
    let enterHoldTimer;
    let isEnterHeld = false;

    if (heroCtaFill && heroCtaBtn) {
      document.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.repeat && !isEnterHeld) {
          // Prevent default enter behavior if needed
          isEnterHeld = true;

          // Use Tailwind class to trigger the exact same scale animation
          heroCtaBtn.classList.add("is-held");

          // Add active push-down effect to button
          heroCtaBtn.style.transform = "translateY(2px)";
          heroCtaBtn.style.boxShadow = "0 5px 15px rgba(212,175,55,0.3)";

          enterHoldTimer = setTimeout(() => {
            // Completed
            window.location.href = "<?= \yii\helpers\Url::to(['site/join']) ?>";
          }, 1500);
        }
      });

      document.addEventListener("keyup", (e) => {
        if (e.key === "Enter") {
          isEnterHeld = false;
          clearTimeout(enterHoldTimer);

          // Revert styles
          heroCtaBtn.classList.remove("is-held");

          // Revert button
          heroCtaBtn.style.transform = "";
          heroCtaBtn.style.boxShadow = "";
        }
      });
    }

    window.addEventListener("load", () => {
      ScrollTrigger.refresh();
    });
  

    function openProgramModal(btn, e) {
      e.stopPropagation();
      const card = btn.closest('.cf-card');
      const title = card.querySelector('h3').innerText;
      const subtitle = card.querySelector('.text-brand-gold').innerText;
      const desc = card.querySelector('.line-clamp-2').innerText;
      const imgSrc = card.querySelector('img').src;

      document.getElementById('modal-title').innerText = title;
      document.getElementById('modal-subtitle').innerText = subtitle;
      document.getElementById('modal-desc').innerText = desc;
      document.getElementById('modal-img').src = imgSrc;

      const modal = document.getElementById('program-info-modal');
      modal.classList.remove('opacity-0', 'pointer-events-none');
      document.getElementById('program-modal-content').classList.remove('scale-95');
    }

    function closeProgramModal() {
      const modal = document.getElementById('program-info-modal');
      modal.classList.add('opacity-0', 'pointer-events-none');
      document.getElementById('program-modal-content').classList.add('scale-95');
    }
  

    // Transparent to Solid Header transition & WhatsApp Button
    const headerBg = document.getElementById("header-bg");
    const programSection = document.getElementById("program");
    const waButton = document.getElementById("wa-button");
    const heroSection = document.getElementById("beranda") || document.getElementById("about-hero") || document.getElementById("trainer-hero");
    const headerCtaBtn = document.getElementById("header-auth-group") || document.getElementById("header-cta-btn");

    const isTrialPage = window.location.href.includes('site%2Fjoin') || window.location.href.includes('site/join');
    const hasNoHero = !heroSection;

    if (isTrialPage || hasNoHero) {
      if (headerBg) {
        headerBg.classList.remove("opacity-0");
        headerBg.classList.add("opacity-100");
      }
      if (isTrialPage && headerCtaBtn) {
        headerCtaBtn.style.display = 'none';
      } else if (hasNoHero && headerCtaBtn) {
        headerCtaBtn.classList.remove("opacity-0", "pointer-events-none", "-translate-y-2");
        headerCtaBtn.classList.add("opacity-100", "pointer-events-auto", "translate-y-0");
      }
    }

    window.addEventListener("scroll", () => {
      if (isTrialPage || hasNoHero) return; // Keep it solid on trial page or pages without hero

      const threshold = programSection ? programSection.offsetTop - 80 : (heroSection ? (heroSection.offsetHeight * 0.35) : 100);

      if (window.scrollY >= threshold) {
        if (headerBg) {
          headerBg.classList.remove("opacity-0");
          headerBg.classList.add("opacity-100");
        }
        if (headerCtaBtn) {
          headerCtaBtn.classList.remove("opacity-0", "pointer-events-none", "-translate-y-2");
          headerCtaBtn.classList.add("opacity-100", "pointer-events-auto", "translate-y-0");
        }
      } else {
        if (headerBg) {
          headerBg.classList.add("opacity-0");
          headerBg.classList.remove("opacity-100");
        }
        if (headerCtaBtn) {
          headerCtaBtn.classList.add("opacity-0", "pointer-events-none", "-translate-y-2");
          headerCtaBtn.classList.remove("opacity-100", "pointer-events-auto", "translate-y-0");
        }
      }

      if (waButton) {
        const waThreshold = heroSection ? (heroSection.offsetHeight * 0.8) : 500;
        if (window.scrollY >= waThreshold) {
          waButton.classList.remove("scale-0", "opacity-0");
          waButton.classList.add("scale-100", "opacity-100");
        } else {
          waButton.classList.add("scale-0", "opacity-0");
          waButton.classList.remove("scale-100", "opacity-100");
        }
      }
    }, { passive: true });
  

    // Update branches dynamically for the sidebar
    function updateSidebarBranches() {
      if (typeof $ === 'undefined') return;
      const $branch = $('#m-branch');
      if (!$branch.length) return;

      const city = $('#m-city').val();
      $branch.empty();

      if (city === 'Batam') {
        $branch.prop('disabled', false);
        $branch.append(new Option('', '', true, true));
        $branch.append(new Option('Batu Ampar', 'Batu Ampar'));
        $branch.append(new Option('Batu Besar', 'Batu Besar'));
        $branch.append(new Option('MTC', 'MTC'));
      } else if (city === 'Bali') {
        $branch.prop('disabled', false);
        $branch.append(new Option('', '', true, true));
        $branch.append(new Option('Kuta', 'Kuta'));
        $branch.append(new Option('Canggu', 'Canggu'));
      } else {
        $branch.prop('disabled', true);
        $branch.append(new Option('', '', true, true));
      }

      $branch.trigger('change');
    }
  