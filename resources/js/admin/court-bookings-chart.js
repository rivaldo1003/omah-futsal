import {
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    LinearScale,
    Tooltip,
} from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip);

const root = document.querySelector('[data-booking-chart]');

if (root) {
    const canvas = root.querySelector('[data-booking-chart-canvas]');
    const payloadElement = root.querySelector('[data-booking-chart-data]');

    if (canvas && payloadElement) {
        let months = [];

        try {
            months = JSON.parse(payloadElement.textContent || '[]');
        } catch (error) {
            console.error('Unable to read monthly booking chart data.', error);
        }

        if (months.length) {
            const counts = months.map((month) => Number(month.booking_count) || 0);
            const maximum = Math.max(...counts);
            const firstPeakIndex = Math.max(0, counts.indexOf(maximum));
            const axisMaximum = Math.max(3, Math.ceil(maximum / 3) * 3);
            const axisStep = Math.max(1, Math.ceil(axisMaximum / 3));
            const colors = {
                bar: '#cacacb',
                peak: '#707072',
                selected: '#111111',
                grid: '#e5e5e5',
                axis: '#707072',
                ink: '#111111',
            };
            const currency = (amount) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(amount) || 0)}`;
            const reducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;
            const readout = {
                label: root.querySelector('[data-chart-readout="label"]'),
                count: root.querySelector('[data-chart-readout="count"]'),
                value: root.querySelector('[data-chart-readout="value"]'),
            };
            let selectedIndex = firstPeakIndex;

            function updateReadout(index) {
                const month = months[index];
                if (!month) return;

                const values = {
                    label: `${month.label} ${month.year}`,
                    count: new Intl.NumberFormat('id-ID').format(Number(month.booking_count) || 0),
                    value: currency(month.booking_value),
                };

                Object.entries(values).forEach(([key, value]) => {
                    const element = readout[key];
                    if (!element || element.textContent === value) return;

                    element.textContent = value;
                    if (!reducedMotion) {
                        element.classList.remove('is-updating');
                        void element.offsetWidth;
                        element.classList.add('is-updating');
                    }
                });
            }

            function colorsForSelection(index) {
                return months.map((month, monthIndex) => {
                    if (monthIndex === index) return colors.selected;
                    if (Number(month.booking_count) === maximum) return colors.peak;
                    return colors.bar;
                });
            }

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: months.map((month) => month.label),
                    datasets: [{
                        label: 'Booking terkonfirmasi',
                        data: counts,
                        backgroundColor: colorsForSelection(selectedIndex),
                        hoverBackgroundColor: colors.selected,
                        borderColor: colors.selected,
                        borderWidth: 0,
                        borderSkipped: 'bottom',
                        borderRadius: { topLeft: 5, topRight: 5 },
                        maxBarThickness: 42,
                        categoryPercentage: 0.74,
                        barPercentage: 0.84,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: reducedMotion ? false : {
                        duration: 720,
                        easing: 'easeOutQuart',
                        delay(context) {
                            return context.type === 'data' && context.mode === 'default'
                                ? context.dataIndex * 34
                                : 0;
                        },
                    },
                    transitions: {
                        active: { animation: { duration: reducedMotion ? 0 : 150 } },
                        resize: { animation: { duration: 0 } },
                    },
                    interaction: { mode: 'index', intersect: false },
                    onHover(_event, activeElements) {
                        if (activeElements.length) updateReadout(activeElements[0].index);
                    },
                    onClick(_event, activeElements, instance) {
                        if (!activeElements.length) return;

                        selectedIndex = activeElements[0].index;
                        instance.data.datasets[0].backgroundColor = colorsForSelection(selectedIndex);
                        updateReadout(selectedIndex);
                        instance.update('none');
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: colors.ink,
                            bodyColor: colors.ink,
                            borderColor: '#d3dae5',
                            borderWidth: 1,
                            cornerRadius: 0,
                            padding: 12,
                            displayColors: false,
                            titleFont: { weight: '600' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                title(items) {
                                    const month = months[items[0]?.dataIndex];
                                    return month ? `${month.label} ${month.year}` : '';
                                },
                                label(context) {
                                    return `${new Intl.NumberFormat('id-ID').format(counts[context.dataIndex])} booking terkonfirmasi`;
                                },
                                afterLabel(context) {
                                    return currency(months[context.dataIndex]?.booking_value);
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: colors.axis,
                                autoSkip: false,
                                maxRotation: 0,
                                padding: 10,
                                font: { size: 12 },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            max: axisMaximum,
                            ticks: {
                                stepSize: axisStep,
                                precision: 0,
                                color: colors.axis,
                                padding: 10,
                                font: { size: 12 },
                            },
                            grid: { color: colors.grid, drawTicks: false },
                            border: { display: false },
                        },
                    },
                },
            });

            canvas.addEventListener('keydown', (event) => {
                let nextIndex = null;

                if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                    nextIndex = Math.min(selectedIndex + 1, months.length - 1);
                } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                    nextIndex = Math.max(selectedIndex - 1, 0);
                } else if (event.key === 'Home') {
                    nextIndex = 0;
                } else if (event.key === 'End') {
                    nextIndex = months.length - 1;
                }

                if (nextIndex === null) return;

                event.preventDefault();
                selectedIndex = nextIndex;
                chart.data.datasets[0].backgroundColor = colorsForSelection(selectedIndex);
                updateReadout(selectedIndex);
                chart.update('none');
            });
        }
    }
}
