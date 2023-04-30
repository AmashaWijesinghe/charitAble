<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar_dons.php'; ?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/donor/schedulereq.css">


<div class="container" >
<!--    <div class="recentOrders" style="background-color: #0A2558">
-->
    <div class="calender-container">
  <div class="calendar">
    <div id="calendar"></div>
  </div>
</div>
<div class="form-container-req">
<div class="form-inner" id="meal-entry">
<form action="<?php echo URLROOT; ?>/schedulereq_dons/add/<?php echo $data['B_id']; ?>" autocomplete="off" method="POST">
<div class="heading-req">
<h2>RESERVE A MEAL</h2>
</div>

<div class="form">
<div class="input">
<input type="text" name="D_Name" id="D_Name" minlength="4" class="input-field-req" value = "<?php echo $data['D_Name']; ?>" autocomplete="off"/>
<label>Name</label>
<div class="warn"><?php echo $data['D_Name_err']; ?></div>
</div>

<div class="input">
<input type="text" name="D_Tel_No" id="D_Tel_No" minlength="4" class="input-field-req" value = "<?php echo $data['D_Tel_No']; ?>" autocomplete="off"/>
<label>Telephone Number</label>
<div class="warn"><?php echo $data['D_Tel_No_err']; ?></div>
</div>

<div class="input">
<input type="text" name="D_Address" id="D_Address" minlength="4" class="input-field-req" value = "<?php echo $data['D_Address']; ?>" autocomplete="off"/>
<label>Address</label>
<div class="warn"><?php echo $data['D_Address_err']; ?></div>
</div>

<div class="input">
<input type="text" name="Food_Type" id="Food_Type" minlength="4" class="input-field-req" value = "<?php echo $data['Food_Type']; ?>" autocomplete="off"/>
<label>Food Type</label>
<div class="warn"><?php echo $data['Food_Type_err']; ?></div>
</div>

<div class="input">
<input type="text" name="Donation_Quantity" id="Donation_Quantity" minlength="4" class="input-field-req" value = "<?php echo $data['Donation_Quantity']; ?>" autocomplete="off"/>
<label>Donation Quantity</label>
<div class="warn"><?php echo $data['Donation_Quantity_err']; ?></div>
</div>

<div class="input">
<label>Date</label>
<input type="date" name="D_Date" id="D_Date" minlength="4" class="input-field-req" value = "<?php echo $data['D_Date']; ?>" autocomplete="off" />
<div class="warn"><?php echo $data['D_Date_err']; ?></div>
</div>

<div class="input">
<label>Time</label><br>
<select class="dropdown" name="Time" id="Time">
  <option value="breakfast">Breakfast</option>
  <option value="lunch">Lunch</option>
  <option value="dinner">Dinner</option>
  </select>
<div class="warn"><?php echo $data['Time_err']; ?></div>
</div>

<input type="submit" value="Submit" class="btn">

</div>

</form>
</div>

</div>
</div>
</div>
  </div>
  </div>

  <script src="<?php echo URLROOT; ?>/js/donor/main.js"></script>

<?php require APPROOT . '/views/inc/footer.php'; ?>