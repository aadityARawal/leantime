<?php
    defined( 'RESTRICTED' ) or die( 'Restricted access' );
?>

<div class="pageheader">
    <div class="pageicon"><span class="fa fa-address-book"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><?php echo $this->__('headline.all_clients') ?></h1>
    </div>
</div><!--pageheader-->

    <div class="maincontent">
        <div class="maincontentinner">

            <?php echo $this->displayNotification() ?>

            <?php
            if($login::userIsAtLeast('manager')){
            echo $this->displayLink('clients.newClient', "<i class='iconfa-plus'></i> ".$this->__('link.new_client'), null, array('class' => 'btn btn-primary btn-rounded')); ?>
            <?php } ?>

            <button class="btn btn-primary btn-rounded" onclick="document.getElementById('import-csv').click()"><i class='iconfa-plus'></i>&nbsp;Import CSV</button>
            <form enctype="multipart/form-data" id="import-csv-form" name="csvform" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                <input name="csv_file" type="file" id="import-csv"style="display:none;" onchange="document.getElementById('import-csv-form').submit()">
                <input type="submit" style="display:none;"; />
            </form>

            <?php
            if (isset($_FILES)) {
                $filePath = $_SERVER['DOCUMENT_ROOT'] .'/userfiles/'. rand() .'.csv';
                if ( move_uploaded_file( $_FILES['csv_file']['tmp_name'], $filePath ) ) {
                    $CSVImport = new \leantime\core\csvImport($filePath);
                    if ($CSVImport == 200) {
                        echo "File is imported! ";
                    } else if ($CSVImport == 201) {
                        echo '<script>alert("Couldn\'t open file! ");</script>';
                    }
                    unset($_FILES);
                    unset($_POST);
                    echo "<script>document.getElementById('import-csv-form').reset();window.location = '/clients/showAll';</script>";

                }
            }
            ?>

            git <table class="table table-bordered" cellpadding="0" cellspacing="0" border="0" id="allClientsTable">
            <colgroup>
                <col class='con0' />
                <col class='con1' />
                <col class='con0' />
            </colgroup>
            <thead>
                <tr>
                    <th class='head1'><?php echo $this->__('label.client_name'); ?></th>
                    <th class='head0'><?php echo $this->__('label.url') ?></th>
                    <th class='head1'><?php echo $this->__('label.number_of_projects'); ?></th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($this->get('allClients') as $row) { ?>
                <tr>
                    <td>
                <?php echo $this->displayLink('clients.showClient', $this->escape($row['name']), array('id' => $this->escape($row['id']))) ?>
                    </td>
                    <td><a href="<?php $this->e($row['internet']); ?>" target="_blank"><?php $this->e($row['internet']); ?></a></td>
                    <td><?php echo $row['numberOfProjects']; ?></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {

            leantime.clientsController.initClientTable();

            <?php if(isset($_SESSION['userdata']['settings']["modals"]["showClients"]) === false || $_SESSION['userdata']['settings']["modals"]["showClients"] == 0) {     ?>
            leantime.helperController.showHelperModal("showClients");
            <?php
            //Only show once per session
            $_SESSION['userdata']['settings']["modals"]["showClients"] = 1;
            } ?>

        }
    );

</script>