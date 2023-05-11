<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/admin/view_profile.css">


<!-- ========================= Main ==================== -->
<div class="main">
    <?php require APPROOT . '/views/inc/topbar.php'; ?>

    <!-- ======================= Buttons ================== -->
    <div class="btnBox">

        <!-- <a href="<?php echo URLROOT; ?>/beneficiaries/registration_requests"><button class="btn active" ><?php echo $data['beneficiary']->B_Name; ?></button></a> -->
        <!-- <a href="<?php echo URLROOT; ?>/beneficiaries/list_of_beneficiaries"><button class="btn active" >List of beneficiaries</button></a> -->
    </div>

    <!-- ================ Order Details List ================= -->
    <div class="details">
        <div class="recentOrders">
            <h2><?php echo $data['beneficiary']->B_Name; ?></h2>

            <div class="details-card">
                <div class="details-head">Beneficiary ID:-</div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Id; ?></div>
            </div>
            <br />

            <div class="details-card">
                <div class="details-head">Beneficiary Name :- </div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Name; ?></div>
            </div>
            <br />

            <div class="details-card">
                <div class="details-head">Beneficiary Email :-</div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Email; ?></div>
            </div>
            <br />

            <div class="details-card">
                <div class="details-head">Beneficiary Address :-</div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Address; ?></div>
            </div>
            <br />

            <div class="details-card">
                <div class="details-head">Beneficiary TP :-</div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Tpno; ?></div>
            </div>
            <br />

            <div class="details-card">
                <div class="details-head">Beneficiary Description :-</div>
                <div class="details-input"><?php echo $data['beneficiary']->B_Description; ?></div>
            </div>
            <br />
            <div class="button">
                <a
                    href="<?php echo URLROOT; ?>/beneficiaries/approve_request/<?php echo $data['beneficiary']->B_Id; ?>"><button
                        class="btn_1">Approved</button></a>
                    <a
                        href="<?php echo URLROOT; ?>/beneficiaries/deny_request/<?php echo $data['beneficiary']->B_Id; ?>"><button
                            class="btn_1">Deny</button></a>
            </div>
        </div>

        <div class="recentOrders">
            <h2>Registration Letter</h2>
            <!-- pdf should show here -->
            <div class="pdf-container">
                <iframe src="<?php echo URLROOT; ?>/public/pdf/registration Letter 1.pdf"
                    style="height:575px;width:400px;border:5px solid black;"></iframe>
            </div>

        </div>
    </div>
</div>

<!-- =========== Scripts =========  -->
<script src="<?php echo URLROOT ?>/public/js/toggle.js"></script>


</body>

</html>