<?php 

    require 'server/connect.php';

        if(!isset($_SESSION["orgUserName"]) && empty($_SESSION["orgUserName"])){ 
            echo '<script>window.location="./login.php"</script>';
        }
        else {
    
        $userEmail = $_SESSION['orgEmail'];

        $search = mysqli_query($conn,"SELECT * FROM organization WHERE organiserEmail='$userEmail'") or die(mysqli_error($conn)); 
        
        $search = mysqli_fetch_assoc($search);
        $orgID = $search['id'];
        
        $getCollectionDates = mysqli_query($conn,"SELECT * FROM collectionDates WHERE organisationID='$orgID' order by date") or die(mysqli_error($conn)); 
        $datesArray = array();
                    
    	while($singleDate = mysqli_fetch_array($getCollectionDates))
        $datesArray[] = $singleDate; 
        
 ?>
<?php include("./components/header.php") ?>
<?php include("./components/sidebar.php") ?>
      
<style>
    .collectionDate:hover{
        box-shadow: 2px 5px 8px -4px #888888;
    }
</style>
<div class="main-panel">
        <div class="content-wrapper">
          
        <div class="row ml-3">
        <h2>Collection Dates</h2>    
        </div>
         
        <div class="row ml-3 mt-3">
            <?php foreach($datesArray as $singleDate){ 
                $today = date("Y-m-d");
                if($singleDate['date'] >= $today){
            ?>
                <a href="collectionDateDonation.php?date=<?php echo $singleDate['id']; ?>" class="col-md-3 grid-margin stretch-card ">
                  <div class="card collectionDate"  style="background-image: linear-gradient( 156.8deg,  rgba(30,144,231,1) 27.1%, rgba(67,101,225,1) 77.8% );">
                    <div class="card-body align-items-center">
                      <h5 style="text-align:center;line-height:1.5;color:white!important;"><?php echo date('l, jS \of F Y ',strtotime($singleDate['date'])); ?></h5>
                      </div>
                  </div>
                </a>

            <?php } } ?>
            
          </div>

          <div class="row ml-3">
            <form  action="server/addCollectionDate.php" method="POST" class="forms-sample">
                    <div class="form-group">
                      <label for="exampleInputUsername1">Select Date</label>
                      <input type="number" name="orgId" style="display:none" value="<?php echo $search['id']  ?>" />
                      <input id="txtDate" name="collectionDate" required type="date" class="form-control" id="exampleInputUsername1" placeholder="Date">
                    </div>
            
            <input type="submit" value="Add Collection Date" class="btn btn-primary" />
            </form>
          </div>
          
        
        </div>
        <!-- content-wrapper ends -->
<script>
    $(function(){
        var dtToday = new Date();
        
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;
        $('#txtDate').attr('min', maxDate);
    });
</script>
<?php include("./components/footer.php") ?>

<?php } ?>