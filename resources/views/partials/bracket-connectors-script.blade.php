@once
    <script>
        (() => {
            const svgNamespace = 'http://www.w3.org/2000/svg';

            document.querySelectorAll('[data-bracket-connectors]').forEach((board) => {
                const svg = board.querySelector('svg');
                if (!svg) return;

                let frame = 0;
                const draw = () => {
                    frame = 0;

                    const width = Math.max(board.scrollWidth, 1);
                    const height = Math.max(board.scrollHeight, 1);
                    const boardRect = board.getBoundingClientRect();
                    svg.setAttribute('width', width);
                    svg.setAttribute('height', height);
                    svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
                    svg.replaceChildren();

                    const rounds = Array.from(board.querySelectorAll('[data-bracket-round]'))
                        .filter((round) => round.dataset.bracketAdvances === 'true');
                    const paths = document.createDocumentFragment();
                    const junctions = document.createDocumentFragment();

                    for (let roundIndex = 0; roundIndex < rounds.length - 1; roundIndex++) {
                        const sourceRound = rounds[roundIndex];
                        const targetRound = rounds[roundIndex + 1];
                        const sources = new Map(
                            Array.from(sourceRound.querySelectorAll('[data-bracket-match-index]'))
                                .map((match) => [Number(match.dataset.bracketMatchIndex), match])
                        );
                        const targets = Array.from(targetRound.querySelectorAll('[data-bracket-match-index]'));

                        targets.forEach((target) => {
                            const targetIndex = Number(target.dataset.bracketMatchIndex);
                            const feederMatches = [sources.get(targetIndex * 2), sources.get(targetIndex * 2 + 1)]
                                .filter(Boolean);
                            if (!feederMatches.length) return;

                            const targetRect = target.getBoundingClientRect();
                            const targetX = targetRect.left - boardRect.left;
                            const targetY = targetRect.top + targetRect.height / 2 - boardRect.top;
                            const feederPoints = feederMatches.map((match) => {
                                const rect = match.getBoundingClientRect();
                                return {
                                    x: rect.right - boardRect.left,
                                    y: rect.top + rect.height / 2 - boardRect.top,
                                };
                            });
                            const junctionX = (feederPoints[0].x + targetX) / 2;
                            let pathData;

                            if (feederPoints.length === 2) {
                                const junctionY = (feederPoints[0].y + feederPoints[1].y) / 2;
                                pathData = [
                                    `M ${feederPoints[0].x} ${feederPoints[0].y} H ${junctionX} V ${junctionY}`,
                                    `M ${feederPoints[1].x} ${feederPoints[1].y} H ${junctionX} V ${junctionY}`,
                                    `M ${junctionX} ${junctionY} V ${targetY} H ${targetX}`,
                                ].join(' ');

                                const junction = document.createElementNS(svgNamespace, 'circle');
                                junction.setAttribute('class', 'standings-bracket-connector-junction v3-bracket-connector-junction');
                                junction.setAttribute('cx', junctionX);
                                junction.setAttribute('cy', junctionY);
                                junction.setAttribute('r', '2.25');
                                junctions.appendChild(junction);
                            } else {
                                pathData = `M ${feederPoints[0].x} ${feederPoints[0].y} H ${junctionX} V ${targetY} H ${targetX}`;
                            }

                            const path = document.createElementNS(svgNamespace, 'path');
                            path.setAttribute('class', 'standings-bracket-connector-path v3-bracket-connector-path');
                            path.setAttribute('d', pathData);
                            paths.appendChild(path);
                        });
                    }

                    svg.append(paths, junctions);
                };

                const scheduleDraw = () => {
                    if (frame) cancelAnimationFrame(frame);
                    frame = requestAnimationFrame(draw);
                };

                if ('ResizeObserver' in window) {
                    const observer = new ResizeObserver(scheduleDraw);
                    observer.observe(board);
                    board.querySelectorAll('[data-bracket-round], [data-bracket-match-index]').forEach((element) => {
                        observer.observe(element);
                    });
                } else {
                    window.addEventListener('resize', scheduleDraw, { passive: true });
                }

                scheduleDraw();
                if (document.fonts?.ready) document.fonts.ready.then(scheduleDraw);
            });
        })();
    </script>
@endonce
