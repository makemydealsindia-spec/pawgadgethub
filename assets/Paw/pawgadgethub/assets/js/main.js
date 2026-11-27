/**
 * PawGadgetHub: lightweight vanilla JS (no jQuery).
 * Mobile menu, live REST search, scroll-reveal animations, pet filter, copy-link share.
 */
(function () {
	'use strict';

	var header = document.getElementById('masthead');
	var nav = document.getElementById('site-nav');
	var navBtn = document.querySelector('.js-nav-toggle');
	var searchBtn = document.querySelector('.js-search-toggle');
	var searchPanel = document.getElementById('header-search');

	function setNav(open) {
		if (!nav || !navBtn) { return; }
		nav.classList.toggle('is-open', open);
		navBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	function setSearch(open) {
		if (!searchPanel || !searchBtn) { return; }
		if (open) {
			searchPanel.removeAttribute('hidden');
			var input = searchPanel.querySelector('input[type="search"]');
			if (input) { input.focus(); }
		} else {
			searchPanel.setAttribute('hidden', '');
		}
		searchBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	if (navBtn) {
		navBtn.addEventListener('click', function () {
			setNav(navBtn.getAttribute('aria-expanded') !== 'true');
		});
	}

	if (searchBtn) {
		searchBtn.addEventListener('click', function () {
			var willOpen = searchBtn.getAttribute('aria-expanded') !== 'true';
			setSearch(willOpen);
			if (willOpen) { setNav(false); }
		});
	}

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			setNav(false);
			setSearch(false);
			document.querySelectorAll('.pgh-live-results').forEach(function (box) {
				box.setAttribute('hidden', '');
			});
		}
	});

	if (nav) {
		nav.addEventListener('click', function (e) {
			if (e.target.closest && e.target.closest('a')) { setNav(false); }
		});
	}

	if (header) {
		var ticking = false;
		var update = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 8);
			ticking = false;
		};
		window.addEventListener('scroll', function () {
			if (!ticking) {
				window.requestAnimationFrame(update);
				ticking = true;
			}
		}, { passive: true });
		update();
	}

	// Show sticky mobile CTA bar only after scrolling past the intro (> 420px).
	var stickyCta = document.querySelector('.pgh-sticky-cta');
	if (stickyCta) {
		var checkSticky = function () {
			stickyCta.classList.toggle('is-shown', window.scrollY > 420);
		};
		window.addEventListener('scroll', checkSticky, { passive: true });
		checkSticky();
	}

	// Interactive "Browse by Pet" filter tabs on the homepage.
	var petTabs = document.querySelectorAll('.pgh-pet-tab');
	var petCards = document.querySelectorAll('#reviews .pgh-card');
	function filterByPet(pet) {
		if (!petTabs.length || !petCards.length) { return; }
		petTabs.forEach(function (btn) {
			var active = btn.getAttribute('data-filter') === pet;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
		});
		petCards.forEach(function (card) {
			var type = card.getAttribute('data-pet') || 'both';
			var show = pet === 'all' || type === 'both' || type === pet;
			card.style.display = show ? '' : 'none';
		});
	}
	petTabs.forEach(function (btn) {
		btn.addEventListener('click', function () {
			filterByPet(btn.getAttribute('data-filter'));
		});
	});
	document.querySelectorAll('[data-jump-pet]').forEach(function (link) {
		link.addEventListener('click', function (e) {
			var pet = e.currentTarget.getAttribute('data-jump-pet');
			if (pet) { filterByPet(pet); }
		});
	});

	// Live Search Results via WordPress REST API.
	document.querySelectorAll('.pgh-search').forEach(function (form) {
		var input = form.querySelector('.pgh-search__input');
		if (!input) { return; }
		form.style.position = 'relative';
		form.style.overflow = 'visible';

		var box = document.createElement('div');
		box.className = 'pgh-live-results';
		box.setAttribute('hidden', '');
		form.appendChild(box);

		var timer = null;
		var controller = null;

		input.addEventListener('input', function () {
			var q = input.value.trim();
			clearTimeout(timer);
			if (q.length < 2) {
				box.setAttribute('hidden', '');
				box.innerHTML = '';
				return;
			}
			timer = setTimeout(function () {
				if (controller && controller.abort) { controller.abort(); }
				controller = window.AbortController ? new AbortController() : null;
				box.innerHTML = '<div class="pgh-live-results__msg">Searching guides...</div>';
				box.removeAttribute('hidden');

				var apiUrl = '/wp-json/wp/v2/posts?search=' + encodeURIComponent(q) + '&per_page=6&_embed';
				fetch(apiUrl, controller ? { signal: controller.signal } : {})
					.then(function (r) { return r.json(); })
					.then(function (posts) {
						if (!Array.isArray(posts) || !posts.length) {
							box.innerHTML = '<div class="pgh-live-results__msg">No matching guides found. Press Enter for full search.</div>';
							return;
						}
						var html = posts.map(function (p) {
							var thumb = '';
							try {
								var media = p._embedded && p._embedded['wp:featuredmedia'] && p._embedded['wp:featuredmedia'][0];
								if (media && media.source_url) { thumb = media.source_url; }
							} catch (err) {}
							var imgHtml = thumb ? '<img src="' + thumb + '" alt="" width="56" height="38" />' : '<span class="pgh-live-results__ph">&#128062;</span>';
							return '<a class="pgh-live-results__item" href="' + p.link + '">' + imgHtml + '<span>' + p.title.rendered + '</span></a>';
						}).join('');
						box.innerHTML = html;
					})
					.catch(function (err) {
						if (err && err.name === 'AbortError') { return; }
						box.setAttribute('hidden', '');
					});
			}, 260);
		});

		document.addEventListener('click', function (e) {
			if (!form.contains(e.target)) { box.setAttribute('hidden', ''); }
		});
	});

	// Copy Link button in Social Share Bar.
	document.querySelectorAll('.js-copy-link').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var url = btn.getAttribute('data-url') || window.location.href;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(function () {
					var label = btn.querySelector('span') || btn;
					var orig = label.textContent;
					label.textContent = 'Copied!';
					btn.classList.add('is-copied');
					setTimeout(function () {
						label.textContent = orig;
						btn.classList.remove('is-copied');
					}, 1800);
				});
			}
		});
	});

	// Smooth Scroll-Reveal Animations.
	var revealTargets = document.querySelectorAll('.pgh-catcard, .pgh-card, .pgh-pillar, .pgh-trustbar__item');
	if ('IntersectionObserver' in window && revealTargets.length) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });
		revealTargets.forEach(function (el, idx) {
			el.classList.add('pgh-reveal');
			el.style.transitionDelay = ((idx % 4) * 55) + 'ms';
			io.observe(el);
		});
	}

	// Responsive stacked card labels for comparison tables on tablet & mobile.
	document.querySelectorAll('.pgh-table-wrap table').forEach(function (tbl) {
		var headerRow = tbl.querySelector('thead tr') || tbl.querySelector('tr');
		if (!headerRow) { return; }
		var labels = [];
		headerRow.querySelectorAll('th').forEach(function (th) {
			labels.push(th.textContent.trim());
		});
		if (!labels.length) { return; }
		if (!tbl.querySelector('thead')) {
			headerRow.classList.add('pgh-table-head-row');
		}
		tbl.querySelectorAll('tbody tr, tr').forEach(function (tr) {
			if (tr === headerRow) { return; }
			tr.querySelectorAll('td').forEach(function (td, i) {
				if (labels[i] && !td.getAttribute('data-label')) {
					td.setAttribute('data-label', labels[i]);
				}
			});
		});
	});
})();
