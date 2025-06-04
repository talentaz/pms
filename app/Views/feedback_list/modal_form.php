

<div class="modal-body clearfix">
    <div class="container-fluid">
        <h1><?php echo $form_title; ?> - Results</h1>
        <?php if ($csv_file): ?>
            <a href="<?php echo base_url('uploads/feedback_csv/' . $csv_file); ?>"><?php echo $csv_file; ?></a>
        <?php endif; ?>
        <?php foreach ($form_data as $question): ?>
            <div class="question-container">
                <div class="chart-title"><?php echo $question['question_title']; ?></div>
                
                <?php if ($question['question_type'] == 'single_choice' || $question['question_type'] == 'multi_choice'): ?>
                    <div class="chart-container">
                        <canvas id="chart-<?php echo $question['question_id']; ?>"></canvas>
                    </div>
                    
                    <script>
                    $(document).ready(function() {
                        // Prepare data for this question
                        const labels = <?php echo json_encode(array_column($question['options'], 'label')); ?>;
                        const counts = <?php echo json_encode(array_column($question['options'], 'count')); ?>;
                        const totalResponses = counts.reduce((a, b) => a + b, 0);
                        
                        // Truncate long labels
                        const truncatedLabels = labels.map(label => {
                            const maxLength = 40;
                            return label.length > maxLength ? label.substring(0, maxLength) + '...' : label;
                        });
                        
                        // Create chart
                        const ctx = document.getElementById('chart-<?php echo $question['question_id']; ?>').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: truncatedLabels,
                                datasets: [{
                                    data: counts,
                                    backgroundColor: [
                                        '#36A2EB'
                                    ],
                                    barThickness: 'flex',
                                    maxBarThickness: 20,
                                    // barPercentage: 0.5,
                                    // categoryPercentage: 0.5 
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            title: function(tooltipItems) {
                                                return labels[tooltipItems[0].dataIndex];
                                            },
                                            label: function(tooltipItem) {
                                                const percentage = ((tooltipItem.raw / totalResponses) * 100).toFixed(1);
                                                return `Count: ${tooltipItem.raw} (${percentage}%)`;
                                            }
                                        }
                                    },
                                    datalabels: {
                                        anchor: 'center',
                                        align: 'end',
                                        formatter: function(value) {
                                            const percentage = ((value / totalResponses) * 100).toFixed(1);
                                            return `${value} (${percentage}%)`;
                                        },
                                        color: 'black',
                                        font: { size: 12 },
                                        padding: { right: 10 }
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: { padding: 10 },
                                        grid: { display: true }
                                    },
                                    y: {
                                        ticks: { padding: 10 },
                                        grid: { display: false }
                                    }
                                },
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 50,
                                        top: 10,
                                        bottom: 10
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });
                    });
                    </script>
                    
                <?php else: ?>
                    <p>Total Responses: <?php echo $question['response_count']; ?></p>
                    <p>Text responses cannot be displayed in chart format.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    
</div>
<style>
    .chart-container {
            position: relative;
            height: 200px;
            width: 100%;
        }
        .chart-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .question-container {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
  </style>
<!-- <script>
     $(document).ready(function() {
    function truncateLabel(label, maxLength) {
        if (label.length > maxLength) {
            return label.substring(0, maxLength) + '...';
        }
        return label;
    }

    const maxLabelLength = 30; // Adjust this value as needed

    const formData = {
        labels: [
            "An object at rest tends to stay at rest unless acted upon by an external force.",
            "Force equals mass times acceleration.",
            "An object in motion tends to stay in motion with constant velocity unless acted upon by an external force.",
            "Every action has an equal and opposite reaction."
        ],
        counts: [1, 2, 2, 3]
    };

    const totalResponses = formData.counts.reduce((a, b) => a + b, 0);
    const truncatedLabels = formData.labels.map(label => truncateLabel(label, maxLabelLength));
    const ctx = document.getElementById('formChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: truncatedLabels,
            datasets: [{
                data: formData.counts,
                backgroundColor: '#36A2EB',
                barThickness: 'flex', // Responsive bar width
                maxBarThickness: 35   // Limits maximum bar thickness
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bars
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }, // Hide legend
                tooltip: {
                    enabled: true,
                    callbacks: {
                        title: function(tooltipItems) {
                            return formData.labels[tooltipItems[0].dataIndex];
                        },
                        label: function(tooltipItem) {
                            return `Count: ${tooltipItem.raw}`;
                        }
                    }
                },
                datalabels: {
                    anchor: 'center',
                    align: 'end',
                    formatter: function(value, context) {
                        const percentage = ((value / totalResponses) * 100).toFixed(1);
                        return `${value} (${percentage}%)`;
                    },
                    color: 'black', // Change color as needed
                }
            },
            scales: {
                x: { display: true, grid: { display: true } },
                y: { display: true, grid: { display: false } }
            },
            layout: {
                padding: { left: 0, right: 0, top: 0, bottom: 0 }
            }
        },
        plugins: [ChartDataLabels] // Register the plugin
    });

    // Handle window resizing
    window.addEventListener('resize', function() {
        chart.resize(); // Ensures responsiveness
    });
});

</script> -->
 