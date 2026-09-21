<script>
    // ==========================================================================
    // TEAM SEARCH & SORTING
    // ==========================================================================
    function initTeamSearch() {
        const teamSearch = document.getElementById('teamSearch');
        const teamSort = document.getElementById('teamSort');
        const teamsGrid = document.getElementById('teamsGrid');
        const noTeamsFound = document.getElementById('noTeamsFound');
        const teamCards = document.querySelectorAll('#teamsGrid .team-card');

        if (!teamSearch || !teamCards.length) return;

        teamSearch.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            teamCards.forEach(card => {
                const teamName = (card.getAttribute('data-team-name') || '').toLowerCase();
                const cardContent = card.textContent.toLowerCase();

                if (teamName.includes(searchTerm) || cardContent.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                teamsGrid.classList.add('d-none');
                noTeamsFound.classList.remove('d-none');
            } else {
                teamsGrid.classList.remove('d-none');
                noTeamsFound.classList.add('d-none');
                if (teamSort) {
                    sortTeams(teamSort.value, teamCards);
                }
            }
        });

        if (teamSort) {
            teamSort.addEventListener('change', function () {
                sortTeams(this.value, teamCards);
            });
        }
    }

    function sortTeams(sortBy, teamCards) {
        const cards = Array.from(teamCards).filter(card => card.style.display !== 'none');

        cards.sort((a, b) => {
            if (sortBy === 'name') {
                const nameA = a.getAttribute('data-team-name') || '';
                const nameB = b.getAttribute('data-team-name') || '';
                return nameA.localeCompare(nameB);
            } else if (sortBy === 'players') {
                const playersA = parseInt(a.getAttribute('data-team-players') || 0, 10);
                const playersB = parseInt(b.getAttribute('data-team-players') || 0, 10);
                return playersB - playersA;
            } else if (sortBy === 'tournaments') {
                const tourneysA = parseInt(a.getAttribute('data-team-tournaments') || 0, 10);
                const tourneysB = parseInt(b.getAttribute('data-team-tournaments') || 0, 10);
                return tourneysB - tourneysA;
            }
            return 0;
        });

        if (cards.length > 0) {
            const container = cards[0].parentNode;
            cards.forEach(card => container.appendChild(card));
        }
    }

    // ==========================================================================
    // TEAM DETAILS MODAL & PLAYER PERFORMANCE ANALYTICS
    // ==========================================================================
    let playerRadarChartInstance = null;

    function initTeamDetailsModal() {
        const teamDetailsModalEl = document.getElementById('teamDetailsModal');
        const viewTeamButtons = document.querySelectorAll('.view-team-details');

        if (!teamDetailsModalEl || !viewTeamButtons.length) return;

        const modal = new bootstrap.Modal(teamDetailsModalEl);

        viewTeamButtons.forEach(button => {
            button.addEventListener('click', function () {
                const teamCard = this.closest('.team-card');
                showTeamPlayers(teamCard, modal);
            });
        });
    }

    function showTeamPlayers(teamCard, modal) {
        if (!teamCard) return;

        const teamName = teamCard.querySelector('h6')?.textContent.trim() || 'Team';
        const coachName = teamCard.getAttribute('data-team-coach') || '';
        const teamLogo = teamCard.getAttribute('data-team-logo') || '';
        const totalPlayers = parseInt(teamCard.getAttribute('data-team-players') || 0, 10);

        let currentPlayers = [];
        try {
            const playersJson = teamCard.getAttribute('data-players-json');
            if (playersJson && playersJson.trim() !== '') {
                const cleanJson = playersJson
                    .replace(/&quot;/g, '"')
                    .replace(/&#039;/g, "'")
                    .replace(/&amp;/g, '&')
                    .replace(/&lt;/g, '<')
                    .replace(/&gt;/g, '>');
                currentPlayers = JSON.parse(cleanJson);
            }
        } catch (e) {
            console.error('Error parsing team players data:', e);
            currentPlayers = [];
        }

        const contentDiv = document.getElementById('teamDetailsContent');
        if (!contentDiv) return;

        let logoHtml = '';
        if (teamLogo && teamLogo.trim() !== '') {
            logoHtml = `<img src="/storage/${teamLogo}" alt="${teamName}" class="team-card-logo" style="width: 44px; height: 44px;">`;
        } else {
            const initials = teamName.substring(0, 2).toUpperCase();
            logoHtml = `<div class="team-card-logo-fallback" style="width: 44px; height: 44px;">${initials}</div>`;
        }

        const playersHtml = generatePlayersHtml(currentPlayers);

        contentDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between p-3 mb-4 rounded border" style="background-color: var(--surface-subtle); border-color: var(--border-color) !important;">
                <div class="d-flex align-items-center gap-3">
                    ${logoHtml}
                    <div>
                        <h5 class="fw-bold mb-0">${teamName}</h5>
                        <div class="text-secondary small">
                            ${coachName ? `<span><i class="bi bi-person me-1"></i>${coachName}</span> • ` : ''}
                            <span><i class="bi bi-people me-1"></i>${totalPlayers} registered players</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="selectedPlayerStats" class="d-none"></div>

            <div class="mb-2">
                <span class="fw-semibold small text-secondary">Click a player to view individual attributes</span>
            </div>

            <div class="player-squad-grid" id="playersGrid">
                ${playersHtml || '<div class="text-center py-4 text-secondary w-100">No players found in this team roster</div>'}
            </div>
        `;

        const selectedPlayerStats = contentDiv.querySelector('#selectedPlayerStats');

        contentDiv.querySelectorAll('.player-modal-card').forEach(playerCard => {
            playerCard.addEventListener('click', function () {
                contentDiv.querySelectorAll('.player-modal-card').forEach(card => {
                    card.classList.remove('is-selected');
                });
                this.classList.add('is-selected');

                const playerName = this.getAttribute('data-player-name') || 'Unknown';
                const goals = parseInt(this.getAttribute('data-player-goals') || '0', 10);
                const assists = parseInt(this.getAttribute('data-player-assists') || '0', 10);
                const appearances = parseInt(this.getAttribute('data-player-appearances') || '0', 10);
                const saves = parseInt(this.getAttribute('data-player-saves') || '0', 10);
                const cleanSheets = parseInt(this.getAttribute('data-player-clean-sheets') || '0', 10);
                const yellowCards = parseInt(this.getAttribute('data-player-yellow-cards') || '0', 10);
                const redCards = parseInt(this.getAttribute('data-player-red-cards') || '0', 10);
                const playerPhoto = this.getAttribute('data-player-photo') || '';
                const playerPosition = this.getAttribute('data-player-position') || '-';
                const playerJersey = this.getAttribute('data-player-jersey') || '-';
                const playerMarketValue = this.getAttribute('data-player-market-value') || '';
                const playerInitial = playerName.charAt(0).toUpperCase();

                const normalizedPos = playerPosition.toLowerCase();
                const isGoalkeeper = normalizedPos.includes('goalkeeper') || normalizedPos.includes('kiper') || normalizedPos.includes('gk');

                const avatarHtml = (playerPhoto && playerPhoto !== '')
                    ? `<img src="${playerPhoto}" alt="${playerName}" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">`
                    : `<div style="width: 54px; height: 54px; border-radius: 50%; background: var(--accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">${playerInitial}</div>`;

                const totalContrib = goals + assists;
                const totalGoalkeeping = saves + cleanSheets;
                const discipline = yellowCards + (redCards * 2);

                const keyStatsHtml = isGoalkeeper ? `
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${appearances}">0</div>
                        <div class="key-stat-lbl">Played</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${saves}">0</div>
                        <div class="key-stat-lbl">Saves</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${cleanSheets}">0</div>
                        <div class="key-stat-lbl">Clean sheets</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num">${totalGoalkeeping}</div>
                        <div class="key-stat-lbl">GK impact</div>
                    </div>
                ` : `
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${appearances}">0</div>
                        <div class="key-stat-lbl">Played</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${goals}">0</div>
                        <div class="key-stat-lbl">Goals</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num" data-target="${assists}">0</div>
                        <div class="key-stat-lbl">Assists</div>
                    </div>
                    <div class="key-stat-cell">
                        <div class="key-stat-num">${totalContrib}</div>
                        <div class="key-stat-lbl">G/A sum</div>
                    </div>
                `;

                selectedPlayerStats.classList.remove('d-none');
                selectedPlayerStats.innerHTML = `
                    <div class="player-analytics-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex align-items-center gap-3">
                                ${avatarHtml}
                                <div>
                                    <h6 class="fw-bold mb-0">${playerName}</h6>
                                    <div class="text-secondary small">
                                        <span>#${playerJersey}</span> •
                                        <span>${playerPosition}</span>
                                        ${playerMarketValue ? ` • <span class="text-primary fw-medium">${playerMarketValue}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                            <span class="app-badge app-badge-accent">Performance analytics</span>
                        </div>

                        <div class="row g-4 align-items-center">
                            <div class="col-12 col-md-5">
                                <div style="height: 190px; position: relative;">
                                    <canvas id="playerRadarChart"></canvas>
                                </div>
                            </div>

                            <div class="col-12 col-md-7">
                                <div class="d-grid gap-2" style="grid-template-columns: repeat(4, 1fr);">
                                    ${keyStatsHtml}
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between small text-secondary">
                                        <span>Disciplinary balance</span>
                                        <span class="text-danger fw-semibold">${discipline} points</span>
                                    </div>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar-fill" style="width: ${Math.min((discipline / 8) * 100, 100)}%; background-color: var(--danger);"></div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <div class="flex-grow-1 text-center py-1 px-2 rounded border" style="background-color: var(--warning-subtle); border-color: rgba(217, 119, 6, 0.2) !important;">
                                        <div class="fw-bold text-warning small">${yellowCards}</div>
                                        <div class="text-secondary" style="font-size: 10px;">Yellow cards</div>
                                    </div>
                                    <div class="flex-grow-1 text-center py-1 px-2 rounded border" style="background-color: var(--danger-subtle); border-color: rgba(220, 38, 38, 0.2) !important;">
                                        <div class="fw-bold text-danger small">${redCards}</div>
                                        <div class="text-secondary" style="font-size: 10px;">Red cards</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Animate numbers smoothly
                selectedPlayerStats.querySelectorAll('.key-stat-num[data-target]').forEach(statEl => {
                    const target = parseInt(statEl.getAttribute('data-target') || '0', 10);
                    let current = 0;
                    const increment = Math.max(1, Math.ceil(target / 15));
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            statEl.textContent = target;
                            clearInterval(timer);
                        } else {
                            statEl.textContent = current;
                        }
                    }, 20);
                });

                initRadarChart(playerName, playerJersey);

                const modalBody = document.querySelector('#teamDetailsModal .modal-body');
                if (modalBody) {
                    modalBody.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        modal.show();
    }

    function initRadarChart(playerName, jersey) {
        const ctx = document.getElementById('playerRadarChart');
        if (!ctx) return;

        if (playerRadarChartInstance) {
            playerRadarChartInstance.destroy();
            playerRadarChartInstance = null;
        }

        const seed = (playerName.length || 5) + parseInt(jersey || 0, 10);
        const stats = [
            65 + (seed % 30),
            60 + ((seed * 2) % 35),
            70 + ((seed * 3) % 25),
            75 + ((seed * 4) % 20),
            68 + ((seed * 5) % 28)
        ];

        playerRadarChartInstance = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Pace', 'Power', 'Shooting', 'Stamina', 'Passing'],
                datasets: [{
                    data: stats,
                    fill: true,
                    backgroundColor: 'rgba(29, 78, 216, 0.12)',
                    borderColor: '#1d4ed8',
                    borderWidth: 1.5,
                    pointBackgroundColor: '#1d4ed8',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: '#e2e8f0' },
                        grid: { color: '#e2e8f0' },
                        pointLabels: {
                            font: { size: 10, weight: '500' },
                            color: '#64748b'
                        },
                        ticks: { display: false },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    function generatePlayersHtml(players) {
        if (!players || players.length === 0) return '';

        let html = '';
        players.forEach(player => {
            const position = (player.position || '').toLowerCase();
            const isGoalkeeper = position.includes('goalkeeper') || position.includes('kiper') || position.includes('gk');

            const photoSource = player.photo
                ? `/storage/${player.photo}`
                : `https://api.dicebear.com/7.x/identicon/svg?seed=${encodeURIComponent(player.name || 'player')}`;

            html += `
                <div class="player-modal-card"
                    data-player-name="${(player.name || 'Unknown').replace(/"/g, '&quot;')}"
                    data-player-goals="${player.goals || 0}"
                    data-player-assists="${player.assists || 0}"
                    data-player-saves="${player.saves || 0}"
                    data-player-clean-sheets="${player.clean_sheets || 0}"
                    data-player-yellow-cards="${player.yellow_cards || 0}"
                    data-player-red-cards="${player.red_cards || 0}"
                    data-player-appearances="${player.appearances || 0}"
                    data-player-photo="${photoSource.replace(/"/g, '&quot;')}"
                    data-player-position="${(player.position || '-').replace(/"/g, '&quot;')}"
                    data-player-jersey="${player.jersey_number || '-'}"
                    data-player-market-value="${(player.market_value || '').replace(/"/g, '&quot;')}">
                    <img src="${photoSource}" alt="${player.name}" class="player-modal-photo">
                    <div class="fw-semibold small text-truncate" title="${player.name || 'Unknown'}">
                        ${player.name || 'Unknown'}
                    </div>
                    <div class="text-secondary" style="font-size: 11px;">
                        #${player.jersey_number || '-'} • ${player.position || '-'}
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-1 mt-1">
                        ${player.market_value ? `<span class="app-badge app-badge-default" style="font-size: 10px;">${player.market_value}</span>` : ''}
                        ${player.goals > 0 ? `<span class="app-badge app-badge-success" style="font-size: 10px;">${player.goals} G</span>` : ''}
                        ${isGoalkeeper && (player.saves || 0) > 0 ? `<span class="app-badge app-badge-accent" style="font-size: 10px;">${player.saves} S</span>` : ''}
                    </div>
                </div>
            `;
        });

        return html;
    }

    // ==========================================================================
    // HERO FULLSCREEN IMAGE VIEWER
    // ==========================================================================
    let heroModalInstance = null;
    let heroScale = 1;

    function initHeroSection() {
        const heroSection = document.getElementById('mainHeroSection');
        if (!heroSection) return;

        const bgImage = heroSection.style.backgroundImage;
        if (bgImage && bgImage !== 'none') {
            heroSection.style.cursor = 'pointer';
            heroSection.addEventListener('click', function (e) {
                if (e.target.closest('.hero-cta-button')) return;

                const match = bgImage.match(/url\(["']?([^"')]+)["']?\)/);
                if (match && match[1]) {
                    const imageUrl = match[1];
                    const title = this.querySelector('.hero-title')?.textContent.trim() || 'Hero image';
                    openHeroFullscreen(imageUrl, title);
                }
            });
        }
    }

    function openHeroFullscreen(imageUrl, title) {
        const modalEl = document.getElementById('heroFullscreenModal');
        const imageContainer = document.getElementById('heroFullscreenImage');
        const downloadBtn = document.getElementById('downloadHeroBtn');

        if (!modalEl || !imageContainer) return;

        heroModalInstance = new bootstrap.Modal(modalEl, { keyboard: true, backdrop: true });

        imageContainer.innerHTML = `
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;

        const img = new Image();
        img.onload = function () {
            imageContainer.innerHTML = `
                <img src="${imageUrl}" alt="${title}" id="heroFullscreenImg"
                    style="max-width: 95vw; max-height: 90vh; object-fit: contain; transition: transform 150ms ease;">
            `;

            if (downloadBtn) {
                downloadBtn.onclick = function () {
                    const link = document.createElement('a');
                    link.href = imageUrl;
                    link.download = `ofs-hero-${Date.now()}.jpg`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                };
            }
        };

        img.onerror = function () {
            imageContainer.innerHTML = `
                <div class="text-white text-center">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                    <p class="mt-2 small">Failed to load image preview.</p>
                </div>
            `;
        };

        img.src = imageUrl;

        modalEl.addEventListener('hidden.bs.modal', function () {
            heroScale = 1;
            imageContainer.innerHTML = '';
            if (heroModalInstance) {
                heroModalInstance.dispose();
                heroModalInstance = null;
            }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        }, { once: true });

        // Mousewheel zoom handling
        heroScale = 1;
        modalEl.onwheel = function (e) {
            e.preventDefault();
            const activeImg = document.getElementById('heroFullscreenImg');
            if (!activeImg) return;

            if (e.deltaY < 0) {
                heroScale = Math.min(heroScale + 0.15, 3);
            } else {
                heroScale = Math.max(heroScale - 0.15, 0.7);
            }
            activeImg.style.transform = `scale(${heroScale})`;
        };

        heroModalInstance.show();
    }

    // ==========================================================================
    // INITIALIZATION ON DOM READY
    // ==========================================================================
    document.addEventListener('DOMContentLoaded', function () {
        initTeamSearch();
        initTeamDetailsModal();
        initHeroSection();
    });

    // Clean modal backdrop handler
    document.addEventListener('hidden.bs.modal', function () {
        setTimeout(() => {
            if (!document.querySelector('.modal.show')) {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }
        }, 150);
    });
</script>
