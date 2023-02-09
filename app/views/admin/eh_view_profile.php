<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/admin/list_of_admins.css">

        
<!-- ========================= Main ==================== -->
        <div class="main">
            <?php require APPROOT . '/views/inc/topbar.php'; ?>

            <!-- ======================= Buttons ================== -->
            <div class="btnBox">
                
                <!-- <a href="<?php echo URLROOT; ?>/beneficiaries/registration_requests"><button class="btn active" ><?php echo $data['donor']->D_fName; ?></button></a> -->
                <!-- <a href="<?php echo URLROOT; ?>/beneficiaries/list_of_beneficiaries"><button class="btn active" >List of beneficiaries</button></a> -->
            </div>
      
            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <h2><?php echo $data['eventHoster']->E_Name; ?></h2>

                    <div class="details-card">
                        <div class="details-head">Event Hoster ID :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_ID; ?></div>
                    <div>
                    <br />

                    <div class="details-card">
                        <div class="details-head">Event Hoster Name :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_Name; ?></div>
                    <div>
                    <br />

                    <div class="details-card">
                        <div class="details-head">Event Hoster Email :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_Email; ?></div>
                    <div>
                    <br />

                    <div class="details-card">
                        <div class="details-head">Event Hoster Address :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_Address; ?></div>
                    <div>
                    <br />

                    <div class="details-card">
                        <div class="details-head">Event Hoster TP :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_tpNo; ?></div>
                    <div>
                    <br />

                    <div class="details-card">
                        <div class="details-head">Event Hoster Description :-</div>
                        <div class="details-input"><?php echo $data['eventHoster']->E_Description; ?></div>
                    <div>
                    <br />
                </div>
            </div>
        </div>

    <!-- =========== Scripts =========  -->
    <script src="<?php echo URLROOT ?>/public/js/toggle.js"></script>

    
</body>

</html>