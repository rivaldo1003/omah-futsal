<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script><script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>

    // Quick debug untuk checkbox
    $('#confirmTournament').on('change', function() {
    });

    // Global variables
    let teamsData = {};
    let groupAssignments = {};
    const groupLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

    // ========== UTILITY FUNCTIONS ==========

    // Fungsi untuk preview logo
    function previewLogo(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('logoPreview');
        const container = document.getElementById('logoPreviewContainer');
        const fileName = document.getElementById('logoFileName');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
                fileName.textContent = file.name;
                
                // Update preview di sidebar
                updateLogoPreview(e.target.result);
                
                // Update review section
                document.getElementById('reviewLogoContainer').innerHTML = 
                    `<img src="${e.target.result}" alt="Tournament Logo" style="max-width: 150px; max-height: 150px; border-radius: 8px;">`;
            };
            reader.readAsDataURL(file);
        } else {
            container.style.display = 'none';
        }
    }

    // Fungsi untuk preview banner
    function previewBanner(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('bannerPreview');
        const container = document.getElementById('bannerPreviewContainer');
        const fileName = document.getElementById('bannerFileName');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
                fileName.textContent = file.name;
                
                // Update review section
                document.getElementById('reviewBannerContainer').innerHTML = 
                    `<img src="${e.target.result}" alt="Tournament Banner" style="max-width: 300px; max-height: 150px; border-radius: 8px;">`;
            };
            reader.readAsDataURL(file);
        } else {
            container.style.display = 'none';
        }
    }

    // Fungsi untuk update preview logo di sidebar
    function updateLogoPreview(imageSrc) {
        const previewContainer = document.getElementById('previewLogoContainer');
        if (previewContainer) {
            previewContainer.innerHTML = `<img src="${imageSrc}" alt="Tournament Logo" style="max-width: 150px; max-height: 150px; border-radius: 8px;">`;
        }
    }

    // Fungsi untuk format date
    function formatDate(dateString) {
        if (!dateString) return '-- -- ----';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // Fungsi untuk show error
    function showError(message, element = null) {
        const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Validation Error:</strong> ${message}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;

        $('.step-content:visible .card-body').prepend(alertHtml);
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if (element) {
            element.focus();
            element[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }


    // ========== FORM VALIDATION ==========

    // Fungsi untuk validate step - PERBAIKAN
    // ========== FORM VALIDATION ==========

    // Fungsi untuk validate step - PERBAIKAN
    function validateStep(step) {
        let isValid = true;
        const tournamentType = $('#type').val();

        if (step === 1) {
            if (!$('#name').val()) {
                showError('Please enter tournament name', $('#name'));
                isValid = false;
            }

            if (!$('#start_date').val()) {
                showError('Please select start date', $('#start_date'));
                isValid = false;
            }

            if (!$('#end_date').val()) {
                showError('Please select end date', $('#end_date'));
                isValid = false;
            }

            if (!$('#type').val()) {
                showError('Please select tournament type', $('#type'));
                isValid = false;
            }
        }

        if (step === 2) {
            const selectedTeams = $('#teams').val();
            if (!selectedTeams || selectedTeams.length === 0) {
                showError('Please select at least one team');
                isValid = false;
            } else if (selectedTeams.length < 2) {
                showError('Please select at least 2 tim');
                isValid = false;
            }
            
            // Validasi khusus untuk knockout
            if (tournamentType === 'knockout') {
                const bracketSize = parseInt($('#knockout_tim').val()) || 8;
                if (selectedTeams.length > bracketSize) {
                    showError(`Knockout tournament can only have ${bracketSize} tim maximum. You selected ${selectedTeams.length} tim.`);
                    isValid = false;
                }
            }
            
            // Validasi untuk group_knockout - minimal tim berdasarkan groups
            if (tournamentType === 'group_knockout') {
                const groupsCount = parseInt($('#groups_count').val()) || 2;
                const timPerGroup = parseInt($('#teams_per_group').val()) || 4;
                const minTeams = groupsCount * 2; // Minimal 2 tim per group
                
                if (selectedTeams.length < minTeams) {
                    showError(`For ${groupsCount} groups, you need at least ${minTeams} tim (2 tim per group)`);
                    isValid = false;
                }
                
                const maxTeams = groupsCount * timPerGroup;
                if (selectedTeams.length > maxTeams) {
                    showError(`Maximum tim for ${groupsCount} groups with ${timPerGroup} tim per group is ${maxTeams}`);
                    isValid = false;
                }
            }
            
            // Validasi untuk league (tambahkan jika perlu)
            if (tournamentType === 'league') {
                const groupsCount = parseInt($('#groups_count').val()) || 1;
                if (groupsCount > 1) {
                    const minTeams = groupsCount * 2;
                    if (selectedTeams.length < minTeams) {
                        showError(`For ${groupsCount} groups league, you need at least ${minTeams} tim`);
                        isValid = false;
                    }
                }
            }
        }

        if (step === 3) {
            const tournamentType = $('#type').val();
            
            // Untuk League - TIDAK PERLU VALIDASI KHUSUS
            if (tournamentType === 'league') {
                // League tidak memerlukan validasi di step 3
                // Karena setup league sederhana (tidak wajib group assignment)
                return true; // Langsung return true untuk league
            }
            
            if (tournamentType === 'group_knockout') {
                // **PERBAIKAN: Tidak wajib semua tim di-assign**
                const assignedTeams = Object.values(teamsData).filter(t => t.assigned).length;
                const totalTeams = Object.keys(teamsData).length;

                if (assignedTeams === 0) {
                    showError('Please assign tim to groups. You can use "Bagi otomatis" button.');
                    isValid = false;
                } else if (assignedTeams < totalTeams) {
                    // **HANYA WARNING, bukan error**
                    // Boleh ada tim yang belum di-assign, sistem akan auto-assign nanti
                }
                
                // Validasi jumlah groups
                const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
                if (groupsCount < 1 || groupsCount > 8) {
                    showError('Number of groups must be between 1 and 8');
                    isValid = false;
                }
            }
            
            // Untuk Knockout, tidak perlu step 3 sama sekali
            if (tournamentType === 'knockout') {
                return true;
            }
        }

        return isValid;
    }

    // ========== FORM SUBMISSION FIX ==========

   // **PERBAIKAN: Ganti seluruh bagian FORM SUBMISSION FIX dengan ini**
    $(document).ready(function () {
        // Debug logging
        
        // Tangani klik tombol Create
        $(document).on('click', '#createBtn', function(e) {
            
            // Cek status checkbox
            const isConfirmed = $('#confirmTournament').is(':checked');
            
            if (!isConfirmed) {
                // Tampilkan error jika checkbox tidak dicentang
                showError('You must confirm that all information is correct before creating tournament.');
                return false;
            }
            
            // Submit form tanpa prevent default
            return true; // Biarkan form submit normal
        });
        
        // Tangani klik tombol Next
        $(document).on('click', '#nextBtn', function(e) {
            
            // Validasi step saat ini
            const currentStep = {{ $currentStep }};
            
            if (validateStep(currentStep)) {
                // Submit form tanpa prevent default
                return true; // Biarkan form submit normal
            } else {
                e.preventDefault(); // Hanya prevent default jika validasi gagal
                return false;
            }
        });
        
        // Tangani klik tombol Previous
        $(document).on('click', '#prevBtn', function(e) {
            // Submit form tanpa prevent default
            return true; // Biarkan form submit normal
        });
    });

   
    


    // ========== PREVIEW UPDATES ==========

    // Fungsi untuk update preview
    function updatePreview() {
        const name = $('#name').val() || '--';
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const location = $('#location').val() || '--';
        const organizer = $('#organizer').val() || '--';
        const type = $('#type').val() || 'league';
        const selectedTeams = $('#teams').val() || [];
        const teamCount = selectedTeams.length;

        // Hitung jumlah pertandingan berdasarkan tipe
        let matchCount = 0;
        if (type === 'league') {
            const rounds = parseInt($('#league_rounds').val()) || 1;
            matchCount = (teamCount * (teamCount - 1) / 2) * rounds;
        } else if (type === 'knockout') {
            const bracketSize = parseInt($('#knockout_tim').val()) || 8;
            matchCount = bracketSize - 1;
            if ($('#knockout_third_place').is(':checked')) {
                matchCount += 1;
            }
        } else if (type === 'group_knockout') {
            const groupsCount = parseInt($('#groups_count').val()) || 2;
            const timPerGroup = parseInt($('#teams_per_group').val()) || 4;
            // Group stage matches
            const groupMatches = groupsCount * (timPerGroup * (timPerGroup - 1) / 2);
            // Knockout matches (qualifiers from each group)
            const qualifyPerGroup = parseInt($('#qualify_per_group').val()) || 2;
            const knockoutTeams = groupsCount * qualifyPerGroup;
            const knockoutMatches = knockoutTeams - 1;
            matchCount = groupMatches + knockoutMatches;
        }

        $('#previewName').text(name);
        $('#previewTeamCount').text(teamCount);
        $('#previewMatchCount').text(matchCount);
        $('#quickTotalMatches').text(matchCount);

        const typeNames = {
            'league': 'League',
            'knockout': 'Knockout',
            'group_knockout': 'Group + Knockout'
        };

        $('#previewTournamentType').text(typeNames[type] || '--');

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

            $('#previewDurationDays').text(duration + ' days');
            $('#quickDuration').text(duration);
            
            // Update review dates
            $('#reviewDates').text(
                `${formatDate(startDate)} to ${formatDate(endDate)}`
            );
        } else {
            $('#previewDurationDays').text('0 days');
            $('#quickDuration').text('0');
        }
        
        // Update review sections
        $('#reviewName').text(name);
        $('#reviewType').text(typeNames[type] || 'Select Type');
        $('#reviewTeams').text(teamCount + ' tim');
        $('#reviewMatches').text(matchCount + ' matches');
        $('#reviewLocation').text(location);
        $('#reviewOrganizer').text(organizer);
        $('#reviewDuration').text(
            `${$('#match_duration').val() || 40} mins (${$('#half_time').val() || 10} mins half)`
        );
        $('#reviewPoints').text(
            `Menang: ${$('#points_win').val() || 3}, Seri: ${$('#points_draw').val() || 1}, Kalah: ${$('#points_loss').val() || 0}`
        );
        $('#reviewSubstitutes').text($('#max_substitutes').val() || 5);
        
        // Update quick stats
        $('#quickTotalTeams').text(teamCount);
        $('#quickGroups').text($('#groups_count').val() || 0);
    }

    // Fungsi untuk update team count
    function updateTeamCount() {
        const selectedTeams = $('#teams').val() || [];
        const totalTeams = $('#teams option').length;

        $('#totalTeamsCount').text(totalTeams);
        $('#selectedTeamsCount').text(selectedTeams.length);
        updatePreview();
    }

    // ========== STEP 3 - GROUP KNOCKOUT ==========

    // Fungsi untuk update step 3 content
    function updateStep3Content(tournamentType) {
        // Hanya group_knockout yang punya konten step 3
        $('.step-content[id^="step3"]').hide();
        $('#step3Label').text('Grup');
        if (tournamentType === 'group_knockout') {
            $('#step3Group').show();
            initializeGroupAssignment();
        }
        updateNextButtonText();
    }

    // Fungsi untuk load tim data
    function loadTeamsData() {
        const selectedTeams = $('#teams').val() || [];
        teamsData = {};

        selectedTeams.forEach(teamId => {
            const teamOption = $('#teams option[value="' + teamId + '"]');
            teamsData[teamId] = {
                id: teamId,
                name: teamOption.data('name') || teamOption.text().split('(')[0].trim(),
                logo: teamOption.data('logo'),
                coach: teamOption.data('coach'),
                group: null,
                seed: 0,
                assigned: false
            };
        });

        const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
        existingAssignments.forEach(assignment => {
            if (assignment.team_id && teamsData[assignment.team_id]) {
                teamsData[assignment.team_id].group = assignment.group;
                teamsData[assignment.team_id].seed = assignment.seed;
                teamsData[assignment.team_id].assigned = true;
            }
        });
    }

    // Fungsi untuk initialize group assignment - PERBAIKAN
    function initializeGroupAssignment() {
        // Load tim data dari select2
        const selectedTeams = $('#teams').val() || [];
        
        // Reset teamsData
        teamsData = {};
        
        // Load tim dari select2
        selectedTeams.forEach(teamId => {
            const teamOption = $('#teams option[value="' + teamId + '"]');
            teamsData[teamId] = {
                id: teamId,
                name: teamOption.data('name') || teamOption.text().split('(')[0].trim(),
                logo: teamOption.data('logo'),
                coach: teamOption.data('coach'),
                group: null,
                seed: 0,
                assigned: false
            };
        });
        
        // Load existing assignments dari hidden input
        const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
        existingAssignments.forEach(assignment => {
            if (assignment.team_id && teamsData[assignment.team_id]) {
                teamsData[assignment.team_id].group = assignment.group;
                teamsData[assignment.team_id].seed = assignment.seed;
                teamsData[assignment.team_id].assigned = true;
            }
        });
        
        // Update groups dan UI
        updateGroups();
        
        // Bagi otomatis jika belum ada assignments
        if (existingAssignments.length === 0 && Object.keys(teamsData).length > 0) {
            setTimeout(() => {
                autoDistribute();
            }, 500);
        }
    }

    // Fungsi untuk update groups
    function updateGroups() {
        const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
        const container = $('#groupsContainer');
        container.empty();

        for (let i = 0; i < groupsCount; i++) {
            const groupLetter = groupLetters[i];
            const groupId = `group-${groupLetter}`;

            const groupHtml = `
            <div class="col-md-${Math.min(12 / groupsCount, 6)} mb-4">
                <div class="card group-container" id="${groupId}" data-group="${groupLetter}">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-folder"></i> Group ${groupLetter}
                            <span class="badge bg-light text-dark float-end" id="group-count-${groupLetter}">0</span>
                        </h6>
                    </div>
                    <div class="card-body group-body" id="group-body-${groupLetter}">
                        <div class="empty-group-message text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Seret tim ke sini</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            <i class="bi bi-people"></i>
                            <span id="group-teams-count-${groupLetter}">0</span> tim
                        </small>
                    </div>
                </div>
            </div>
            `;

            container.append(groupHtml);
        }

        updateAvailableTeams();
        updateAssignedTeamsInGroups();
        updateCounts();
        
        // Re-initialize drag and drop setelah groups diupdate
        setTimeout(() => {
            initializeDragAndDrop();
        }, 100);
    }

    // Fungsi untuk initialize drag and drop
    function initializeDragAndDrop() {
        const availableContainer = document.getElementById('availableTeamsContainer');
        
        if (availableContainer) {
            new Sortable(availableContainer, {
                group: {
                    name: 'shared',
                    pull: 'clone',
                    put: true
                },
                animation: 150,
                sort: false,
                onAdd: function (evt) {
                    const teamId = evt.item.dataset.teamId;
                    removeTeamFromGroup(teamId);
                }
            });
        }

        // Initialize Sortable for each group
        document.querySelectorAll('.group-body').forEach(groupBody => {
            new Sortable(groupBody, {
                group: 'shared',
                animation: 150,
                onAdd: function (evt) {
                    const teamId = evt.item.dataset.teamId;
                    const group = evt.to.parentElement.dataset.group;
                    if (teamId && group) {
                        assignTeamToGroup(teamId, group);
                        updateAvailableTeams();
                    }
                },
                onUpdate: function (evt) {
                    updateSeedsInGroup(evt.to.parentElement.dataset.group);
                    saveGroupAssignments();
                }
            });
        });
    }

    // Fungsi untuk assign team ke group
    function assignTeamToGroup(teamId, group) {
        if (!teamsData[teamId]) return;

        const previousGroup = teamsData[teamId].group;
        if (previousGroup && previousGroup !== group) {
            removeTeamFromGroupDisplay(teamId, previousGroup);
        }

        teamsData[teamId].group = group;
        teamsData[teamId].assigned = true;

        const groupTeams = Object.values(teamsData).filter(t => t.group === group && t.assigned);
        teamsData[teamId].seed = groupTeams.length;

        addTeamToGroupDisplay(teamId, group, teamsData[teamId].seed);
        updateCounts();
        saveGroupAssignments();
    }

    // Fungsi untuk update available tim display
    function updateAvailableTeams() {
        const container = $('#availableTeamsContainer');
        container.empty();

        const unassignedTeams = Object.values(teamsData).filter(team => !team.assigned);

        if (unassignedTeams.length === 0) {
            container.html('<div class="text-center text-muted py-4">All tim have been assigned to groups</div>');
            return;
        }

        unassignedTeams.forEach(team => {
            const teamCard = `
            <div class="col-md-3 mb-3 team-card-container" id="available-team-${team.id}">
                <div class="team-card draggable-item" 
                     data-team-id="${team.id}"
                     draggable="true">
                    <div class="team-logo-placeholder">
                        ${team.logo ? `<img src="${team.logo}" alt="${team.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">` : team.name.substring(0, 2)}
                    </div>
                    <h6 class="mb-1">${team.name}</h6>
                    ${team.coach ? `<small class="text-muted">Coach: ${team.coach}</small>` : ''}
                    <div class="mt-2">
                        <span class="badge bg-warning">Tersedia</span>
                    </div>
                </div>
            </div>
            `;
            container.append(teamCard);
        });

        // Initialize drag and drop untuk available tim
        setTimeout(() => {
            initializeDragAndDrop();
        }, 50);
    }

    // Fungsi untuk add team ke group display
    function addTeamToGroupDisplay(teamId, group, seed) {
        const team = teamsData[teamId];
        if (!team) return;

        const groupBody = document.getElementById(`group-body-${group}`);
        if (!groupBody) return;

        const emptyMessage = groupBody.querySelector('.empty-group-message');
        if (emptyMessage) {
            emptyMessage.remove();
        }

        const existingElement = groupBody.querySelector(`[data-team-id="${teamId}"]`);
        if (existingElement) {
            existingElement.remove();
        }

        const teamElement = document.createElement('div');
        teamElement.className = 'draggable-item';
        teamElement.dataset.teamId = teamId;
        teamElement.draggable = true;

        let logoHtml = '';
        if (team.logo) {
            logoHtml = `<img src="${team.logo}" alt="${team.name}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">`;
        } else {
            logoHtml = `<div class="team-logo-placeholder small me-2" style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), var(--secondary-light)); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">${team.name.substring(0, 2)}</div>`;
        }

        teamElement.innerHTML = `
        <div class="d-flex align-items-center">
            ${logoHtml}
            <div class="flex-grow-1">
                <div class="fw-bold">${team.name}</div>
                ${team.coach ? `<small class="text-muted">Coach: ${team.coach}</small>` : ''}
            </div>
            <div class="ms-2">
                <span class="badge bg-primary">Seed ${seed}</span>
            </div>
        </div>
        `;

        teamElement.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('teamId', teamId);
            this.classList.add('dragging');
        });

        teamElement.addEventListener('dragend', function () {
            this.classList.remove('dragging');
        });

        groupBody.appendChild(teamElement);
        updateGroupCount(group);
    }

    // Fungsi untuk remove team dari group display
    function removeTeamFromGroupDisplay(teamId, group) {
        const groupBody = document.getElementById(`group-body-${group}`);
        if (groupBody) {
            const teamElement = groupBody.querySelector(`[data-team-id="${teamId}"]`);
            if (teamElement) {
                teamElement.remove();
            }

            if (groupBody.children.length === 0) {
                groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Seret tim ke sini</p></div>';
            }
        }
    }

    // Fungsi untuk update seeds in group
    function updateSeedsInGroup(group) {
        const groupBody = document.getElementById(`group-body-${group}`);
        if (!groupBody) return;

        const teamElements = groupBody.querySelectorAll('.draggable-item');
        teamElements.forEach((element, index) => {
            const teamId = element.dataset.teamId;
            if (teamsData[teamId]) {
                teamsData[teamId].seed = index + 1;

                const badge = element.querySelector('.badge');
                if (badge) {
                    badge.textContent = `Seed ${index + 1}`;
                }
            }
        });

        saveGroupAssignments();
    }

    // Fungsi untuk update group count
    function updateGroupCount(group) {
        const groupTeams = Object.values(teamsData).filter(t => t.group === group && t.assigned);
        $(`#group-count-${group}`).text(groupTeams.length);
        $(`#group-teams-count-${group}`).text(groupTeams.length);
    }

    // Fungsi untuk update counts
    function updateCounts() {
        const totalTeams = Object.keys(teamsData).length;
        const assignedTeams = Object.values(teamsData).filter(t => t.assigned).length;
        const unassignedTeams = totalTeams - assignedTeams;
        const groupsCount = parseInt($('#groups_count_assign').val()) || 0;

        $('#assignedCount').text(assignedTeams);
        $('#unassignedCount').text(unassignedTeams);
        $('#groupsCount').text(groupsCount);

        for (let i = 0; i < groupsCount; i++) {
            const groupLetter = groupLetters[i];
            updateGroupCount(groupLetter);
        }
    }

    // Fungsi untuk auto distribute tim - PERBAIKAN
    function autoDistribute() {
        const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
        
        // Reset all tim
        Object.values(teamsData).forEach(team => {
            team.group = null;
            team.seed = 0;
            team.assigned = false;
        });

        const allTeams = Object.keys(teamsData);
        const shuffledTeams = [...allTeams].sort(() => Math.random() - 0.5);

        // Distribute tim secara merata ke semua groups
        shuffledTeams.forEach((teamId, index) => {
            const groupIndex = index % groupsCount;
            const groupLetter = groupLetters[groupIndex];
            teamsData[teamId].group = groupLetter;
            teamsData[teamId].assigned = true;

            // Hitung seed untuk group ini
            const groupTeams = shuffledTeams.filter((tId, idx) =>
                idx % groupsCount === groupIndex && idx <= index
            );
            teamsData[teamId].seed = groupTeams.length;
        });

        updateAvailableTeams();
        updateAssignedTeamsInGroups();
        updateCounts();
        saveGroupAssignments();
    }

    // Fungsi untuk reset groups
    function resetGroups() {
        Object.values(teamsData).forEach(team => {
            team.group = null;
            team.seed = 0;
            team.assigned = false;
        });

        groupLetters.forEach(letter => {
            const groupBody = document.getElementById(`group-body-${letter}`);
            if (groupBody) {
                groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Seret tim ke sini</p></div>';
                $(`#group-count-${letter}`).text('0');
                $(`#group-teams-count-${letter}`).text('0');
            }
        });

        updateAvailableTeams();
        updateCounts();
        saveGroupAssignments();
    }

    // Fungsi untuk remove team dari group
    function removeTeamFromGroup(teamId) {
        if (!teamsData[teamId]) return;

        const group = teamsData[teamId].group;
        teamsData[teamId].group = null;
        teamsData[teamId].seed = 0;
        teamsData[teamId].assigned = false;

        removeTeamFromGroupDisplay(teamId, group);

        if (group) {
            updateSeedsInGroup(group);
        }

        updateAvailableTeams();
        updateCounts();
        saveGroupAssignments();
    }

    // Fungsi untuk save group assignments
    function saveGroupAssignments() {
        const assignments = [];

        Object.values(teamsData).forEach(team => {
            if (team.assigned && team.group) {
                assignments.push({
                    team_id: team.id,
                    group: team.group,
                    seed: team.seed
                });
            }
        });

        $('#groupAssignments').val(JSON.stringify(assignments));
    }

    // Fungsi untuk handle form submission di step 5
    function handleStep5Submission(e) {
        
        // Cek checkbox konfirmasi
        const confirmCheckbox = document.getElementById('confirmTournament');
        if (!confirmCheckbox || !confirmCheckbox.checked) {
            e.preventDefault();
            showError('You must confirm that all information is correct before creating tournament.');
            
            // Scroll ke checkbox
            confirmCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            confirmCheckbox.focus();
            return false;
        }
        
        // **PERBAIKAN: Simpan semua data form ke localStorage sebagai backup**
        const formData = new FormData(document.getElementById('tournamentForm'));
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });
        
        localStorage.setItem('tournament_form_backup', JSON.stringify(formObject));
        
        return true;
    }

// Pasang event listener untuk form submission di step 5
$(document).ready(function() {
    // Debug info
    
    // Tangani form submission
    $('#tournamentForm').on('submit', function(e) {
        const currentStep = {{ $currentStep }};
        const formAction = $('button[type="submit"]:focus').val() || $('input[name="form_action"]').val() || 'next';
        
        if (currentStep === 5) {
            return handleStep5Submission(e);
        }
        
        // Untuk step 1-4, validasi dulu
        if (!validateStep(currentStep)) {
            e.preventDefault();
            return false;
        }
        return true;
    });
    
    // **PERBAIKAN: Simpan form state ke localStorage secara berkala**
    setInterval(function() {
        if ($('#tournamentForm').length) {
            const currentStep = {{ $currentStep }};
            const formData = {
                step: currentStep,
                data: {}
            };
            
            // Simpan data form yang penting
            $('#tournamentForm').find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (name && !name.includes('_token')) {
                    if ($(this).is(':checkbox') || $(this).is(':radio')) {
                        formData.data[name] = $(this).is(':checked');
                    } else {
                        formData.data[name] = $(this).val();
                    }
                }
            });
            
            localStorage.setItem('tournament_form_autosave', JSON.stringify(formData));
        }
    }, 5000); // Autosave setiap 5 detik
    
    // **PERBAIKAN: Coba restore dari localStorage jika session kosong**
    @if(empty($tournamentData) && $currentStep > 1)
        const savedForm = localStorage.getItem('tournament_form_autosave');
        if (savedForm) {
            // Bisa digunakan untuk recovery jika diperlukan
        }
    @endif
});


// **PERBAIKAN: Function untuk handle semua tombol submit**
$(document).on('click', 'button[type="submit"]', function() {
    const currentStep = {{ $currentStep }};
    const buttonValue = $(this).val();
    
    // Set form action berdasarkan tombol yang diklik
    if (buttonValue === 'create') {
        $('#formAction').val('create');
    } else {
        $('#formAction').val('next');
    }
    
    // Untuk step 5, validasi checkbox
    if (currentStep === 5 && buttonValue === 'create') {
        const confirmCheckbox = document.getElementById('confirmTournament');
        if (!confirmCheckbox || !confirmCheckbox.checked) {
            showError('You must confirm that all information is correct before creating tournament.');
            confirmCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            confirmCheckbox.focus();
            return false;
        }
    }
    
    return true;
});

    // Fungsi untuk update assigned tim in groups
    function updateAssignedTeamsInGroups() {
        groupLetters.forEach(letter => {
            const groupBody = document.getElementById(`group-body-${letter}`);
            if (groupBody) {
                groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Seret tim ke sini</p></div>';
            }
        });

        Object.values(teamsData).forEach(team => {
            if (team.group && team.assigned) {
                addTeamToGroupDisplay(team.id, team.group, team.seed);
            }
        });
    }

    // ========== MAIN DOCUMENT READY ==========

    $(document).ready(function () {
        // Initialize Select2
        $('#teams').select2({
            placeholder: 'Select tim to participate',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        // Initialize step 3 content berdasarkan tipe yang dipilih
        const tournamentType = $('#type').val() || 'group_knockout';
        
        // Update step 3 label saat awal load
        const step3Labels = {
            'league': 'Setup League',
            'knockout': 'Setup Bracket',
            'group_knockout': 'Groups'
        };
        $('#step3Label').text(step3Labels[tournamentType] || 'Groups');
        
        // Jika di step 3, tampilkan konten yang sesuai
        if ({{ $currentStep }} == 3) {
            updateStep3Content(tournamentType);
        }

        // Update next button text saat awal
        updateNextButtonText();

        // Initialize tim data
        @if(!empty($tournamentData['teams']))
            @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                @php
                    $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                @endphp
                teamsData[{{ $team->id }}] = {
                    id: {{ $team->id }},
                    name: "{{ $team->name }}",
                    logo: "{{ $logoUrl }}",
                    coach: "{{ $team->coach_name }}",
                    group: null,
                    seed: 0,
                    assigned: false
                };
            @endforeach
        @endif

        // Load existing assignments
        const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
        if (existingAssignments.length > 0) {
            existingAssignments.forEach(assignment => {
                if (assignment.group && assignment.team_id && teamsData[assignment.team_id]) {
                    teamsData[assignment.team_id].group = assignment.group;
                    teamsData[assignment.team_id].seed = assignment.seed;
                    teamsData[assignment.team_id].assigned = true;
                }
            });
        }

        // Initialize groups
        updateGroups();

        // Event listeners
        $('#teams').on('change', function () {
            updateTeamCount();
            updatePreview();
            loadTeamsData();
            updateGroups();
        });

        $('#name').on('input', function () {
            const name = $(this).val();
            const slugInput = $('#slug');

            if (!slugInput.data('customized')) {
                const slug = name.toLowerCase()
                    .replace(/[^\w\s]/gi, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');

                if (slug) {
                    slugInput.val(slug);
                }
            }

            updatePreview();
        });

        $('#slug').on('input', function () {
            $(this).data('customized', true);
        });

        // Tournament type change handler
        $('#type').on('change', function () {
        const selectedType = $(this).val();
        
        // Hide all settings sections
        $('#groupSettings, #leagueSettings, #knockoutSettings').hide();
        // Tiebreaker rules hanya relevan untuk group_knockout
        $('#tiebreakerSettings').toggle(selectedType === 'group_knockout');
        
        // Show relevant settings section
        if (selectedType === 'group_knockout') {
            $('#groupSettings').show();
            $('#groups_count').prop('required', true);
            $('#teams_per_group').prop('required', true);
            $('#qualify_per_group').prop('required', true);
        } else if (selectedType === 'league') {
            $('#leagueSettings').show();
            // League bisa memiliki grup atau tidak
            $('#groups_count').prop('required', false);
            $('#teams_per_group').prop('required', false);
            $('#qualify_per_group').prop('required', false);
            
            // **FIX: Reset nilai groups_count jika league tanpa grup**
            if (!$('#groups_count').val() || $('#groups_count').val() < 1) {
                $('#groups_count').val(1); // Default 1 group untuk league
            }
        } else if (selectedType === 'knockout') {
            $('#knockoutSettings').show();
            $('#groups_count').prop('required', false);
            $('#teams_per_group').prop('required', false);
            $('#qualify_per_group').prop('required', false);
        }
        
        // Jika di step 3, update konten
        if ({{ $currentStep }} == 3) {
            updateStep3Content(selectedType);
        }
        
        // Update step 3 label
        const step3Labels = {
            'league': 'Setup League',
            'knockout': 'Setup Bracket',
            'group_knockout': 'Groups'
        };
        $('#step3Label').text(step3Labels[selectedType] || 'Groups');
        
        // Update preview dan next button text
        updatePreview();
        updateNextButtonText();
    });

        // Preview fields update
        const previewFields = [
            'name', 'start_date', 'end_date', 'location', 'organizer', 'type',
            'groups_count', 'teams_per_group', 'qualify_per_group',
            'match_duration', 'half_time', 'extra_time',
            'points_win', 'points_draw', 'points_loss', 'points_no_show',
            'max_substitutes', 'yellow_card_suspension',
            'matches_per_day', 'match_interval', 'match_time_slots'
        ];

        previewFields.forEach(fieldId => {
            $('#' + fieldId).on('input change', updatePreview);
        });

        // Initialize counts and preview
        updateTeamCount();
        updatePreview();
    });

    // Fungsi untuk update next button text
    function updateNextButtonText() {
        const currentStep = {{ $currentStep }};
        const tournamentType = $('#type').val();
        
        if (currentStep < 5) {
            let nextText = '';
            if (currentStep == 1) {
                nextText = 'Pilih Tim';
            } else if (currentStep == 2) {
                if (tournamentType === 'league') {
                    nextText = 'Setup League';
                } else if (tournamentType === 'knockout') {
                    nextText = 'Setup Bracket';
                } else if (tournamentType === 'group_knockout') {
                    nextText = 'Groups';
                } else {
                    nextText = 'Setup';
                }
            } else if (currentStep == 3) {
                nextText = 'Match Rules';
            } else if (currentStep == 4) {
                nextText = 'Review';
            }
            
            $('#nextBtn span').html(`Next: ${nextText}`);
        }
    }

    // Sidebar toggle
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebarToggle');

        // Toggle sidebar on button click
        if (toggleButton) {
            toggleButton.addEventListener('click', function () {
                sidebar.classList.toggle('open');
            });
        }

        // Close sidebar when clicking outside on mobile
        if (sidebar) {
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 992) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = toggleButton && toggleButton.contains(event.target);

                    if (sidebar.classList.contains('open') && !isClickInsideSidebar && !isClickOnToggle) {
                        sidebar.classList.remove('open');
                    }
                }
            });
        }
        
        // Initialize drag and drop jika di step 3
        @if($currentStep == 3)
            setTimeout(() => {
                if ($('#type').val() === 'group_knockout') {
                    initializeGroupAssignment();
                }
            }, 500);
        @endif
    });
</script>

<script>
// ===== Tie-breaker: preset & drag-and-drop (konsisten dengan edit turnamen) =====
document.addEventListener('DOMContentLoaded', function () {
    const tieBreakersContainer = document.getElementById('tie-breakers-container');
    if (!tieBreakersContainer) return;

    const tieBreakerLabels = {
        'points': 'Poin',
        'head_to_head': 'Head-to-head',
        'goal_difference': 'Selisih gol',
        'goals_scored': 'Gol mencetak',
        'fair_play': 'Fair play',
        'penalty': 'Adu penalti'
    };

    const tieBreakerPresets = {
        'gd_first': ['goal_difference', 'head_to_head', 'goals_scored', 'fair_play', 'penalty'],
        'h2h_first': ['head_to_head', 'goal_difference', 'goals_scored', 'fair_play', 'penalty'],
        'fifa': ['points', 'goal_difference', 'goals_scored', 'head_to_head', 'fair_play', 'penalty'],
        'goals_first': ['goals_scored', 'head_to_head', 'goal_difference', 'fair_play', 'penalty']
    };

    const initialKeys = Array.from(tieBreakersContainer.querySelectorAll('.tie-breaker-select')).map(s => s.value);

    function buildItem(key, index) {
        const item = document.createElement('div');
        item.className = 'input-group mb-2 tie-breaker-item';
        item.draggable = true;
        item.style.cursor = 'move';
        item.innerHTML = `
            <span class="input-group-text bg-light text-secondary fw-bold drag-handle" style="cursor: grab;">
                <i class="bi bi-grip-vertical"></i> ${index + 1}
            </span>
            <select class="form-select tie-breaker-select" name="tie_breakers[${index}][key]" required>
                ${Object.entries(tieBreakerLabels).map(([k, label]) =>
                    `<option value="${k}" ${k === key ? 'selected' : ''}>${label}</option>`
                ).join('')}
            </select>
            <button type="button" class="btn btn-outline-danger remove-rule">
                <i class="bi bi-trash"></i>
            </button>
        `;
        return item;
    }

    function reindex() {
        tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach((item, index) => {
            const handle = item.querySelector('.drag-handle');
            if (handle) handle.innerHTML = `<i class="bi bi-grip-vertical"></i> ${index + 1}`;
            const select = item.querySelector('.tie-breaker-select');
            if (select) select.name = `tie_breakers[${index}][key]`;
        });
    }

    function attachEvents(item) {
        item.querySelector('.remove-rule').addEventListener('click', function () {
            item.remove();
            reindex();
        });

        item.addEventListener('dragstart', function (e) {
            draggedTieItem = this;
            this.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
        });

        item.addEventListener('dragend', function () {
            this.style.opacity = '1';
            draggedTieItem = null;
            tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(i => {
                i.style.borderTop = '';
                i.style.borderBottom = '';
            });
        });

        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (draggedTieItem && draggedTieItem !== this) {
                const rect = this.getBoundingClientRect();
                const offset = rect.y + rect.height / 2;
                if (e.clientY - offset > 0) {
                    this.style.borderBottom = '2px solid var(--secondary)';
                    this.style.borderTop = '';
                } else {
                    this.style.borderTop = '2px solid var(--secondary)';
                    this.style.borderBottom = '';
                }
            }
        });

        item.addEventListener('dragleave', function () {
            this.style.borderTop = '';
            this.style.borderBottom = '';
        });

        item.addEventListener('drop', function (e) {
            e.preventDefault();
            this.style.borderTop = '';
            this.style.borderBottom = '';
            if (draggedTieItem && draggedTieItem !== this) {
                const rect = this.getBoundingClientRect();
                const insertBefore = e.clientY - (rect.y + rect.height / 2) < 0;
                if (insertBefore) {
                    this.parentNode.insertBefore(draggedTieItem, this);
                } else {
                    this.parentNode.insertBefore(draggedTieItem, this.nextSibling);
                }
                reindex();
            }
        });
    }

    let draggedTieItem = null;

    function applyKeys(keys) {
        tieBreakersContainer.innerHTML = '';
        keys.forEach((key, index) => {
            const item = buildItem(key, index);
            tieBreakersContainer.appendChild(item);
            attachEvents(item);
        });
    }

    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const keys = tieBreakerPresets[this.dataset.preset];
            if (!keys) return;
            applyKeys(keys);
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    const resetBtn = document.getElementById('resetTieBreakers');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            applyKeys(initialKeys);
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
        });
    }

    const addRuleBtn = document.getElementById('add-rule');
    if (addRuleBtn) {
        addRuleBtn.addEventListener('click', function () {
            const count = tieBreakersContainer.querySelectorAll('.tie-breaker-item').length;
            const item = buildItem('', count);
            tieBreakersContainer.appendChild(item);
            attachEvents(item);
        });
    }

    tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(attachEvents);
});
</script>
