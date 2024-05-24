var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['2023', '2024'],
                datasets: [{
                    label: 'Nombre de visiteurs 2023',
                    data: [<?php foreach ($data_2023 as $row) { echo $row['total'] . ", "; } ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Nombre de visiteurs 2024',
                    data: [<?php foreach ($data_2024 as $row) { echo $row['total'] . ", "; } ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });