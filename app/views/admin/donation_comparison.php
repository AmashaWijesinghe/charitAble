<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/admin/lists.css">


<!-- ========================= Main ==================== -->
<div class="main">
    <?php require APPROOT . '/views/inc/topbar.php'; ?>
    <!-- ================ Order Details List ================= -->
    <div class="details">
        <div class="recentOrders">
            <h2>Expired Donations</h2>
            <div class="chart" style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center">
                <div class="chart1" style="width: 100%; display: flex; flex-direction: column">
                    <canvas id="myLine"></canvas>
                    <button onclick="generatePDF()">Generate PDF</button>
                </div>

            </div>

            <script>
            var count;
            var req;

            const ctx = document.getElementById('myLine');

            function donation_comparison() {

                $.ajax({
                    url: "http://localhost/charitAble/DonationComparison/lineChart",
                    method: 'GET',
                    dataType: 'JSON',
                    success: function(response) {

                        console.log(response);

                        // setup block
                        const data = {

                            labels: [response.jan, response.feb, response.mar, response.apr, response
                                .may, response.jun, response.jul, response.aug, response.sep,
                                response.oct, response.nov, response.dec
                            ],
                            datasets: [{
                                label: 'Expired Donations in 2023',
                                data: [response.janCount.num_rows, response.febCount.num_rows,
                                    response.marCount.num_rows, response.aprCount.num_rows,
                                    response.mayCount.num_rows, response.junCount.num_rows,
                                    response.julCount.num_rows, response.augCount.num_rows,
                                    response.sepCount.num_rows, response.octCount.num_rows,
                                    response.novCount.num_rows, response.decCount.num_rows
                                ],
                                borderWidth: 2,
                                yAxisID: 'y'
                            }
                            // {
                            //     label: 'Expired Donations in 2023',
                            //     data: [response.janCount.num_rows, response.febCount.num_rows,
                            //         response.marCount.num_rows, response.aprCount.num_rows,
                            //         response.mayCount.num_rows, response.junCount.num_rows,
                            //         response.julCount.num_rows, response.augCount.num_rows,
                            //         response.sepCount.num_rows, response.octCount.num_rows,
                            //         response.novCount.num_rows, response.decCount.num_rows
                            //     ],
                            //     borderWidth: 2
                            // }
                        ],
                        };

                        const bgColor = {
                            id: 'bgColor',
                            beforeDraw: (chart, options) => {
                                const {
                                    ctx,
                                    width,
                                    height
                                } = chart;
                                ctx.fillStyle = 'white'
                                ctx.fillRect(0, 0, width, height)
                                ctx.restore()
                            }
                        };

                        //config block
                        const config = {
                            type: 'line',
                            data,
                            options: {
                                scales: {
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                    }
                                }
                            },
                            plugins: [bgColor]
                        };
                        //Render block
                        const myLine = new Chart(
                            document.getElementById('myLine'),
                            config

                        );


                    }
                })
            }

            donation_comparison();

            function generatePDF() {
                const canvas = document.getElementById('myLine')
                const canvasImage = canvas.toDataURL('image/jpeg', 1.0)
                let pdf = new jsPDF()
                pdf.setFontSize(20)
                pdf.addImage(canvasImage, 'JPEG', 15, 15, 150, 150)
                pdf.save("myLine.pdf")
            }
            </script>
        </div>
    </div>
</div>

</body>



</html>