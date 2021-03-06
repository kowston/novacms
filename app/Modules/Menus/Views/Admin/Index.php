<section class="content-header">
    <h1>Menus</h1>
    <ol class="breadcrumb">
        <li><a href='<?= admin_url('dashboard'); ?>'><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Menus</li>
    </ol>
</section>

<section class='content'>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Menus</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">

		<?=Session::getMessages();?>

		<p><a href='<?=admin_url('menus/create');?>' class='btn btn-info btn-xs'><i class='fa fa-plus'></i> Create Menu</a></p>

		<div class='table-responsive'>
        <table class='table table-striped table-hover table-bordered'>
        <tr>
        	<th>Title</th>
        	<th>Tag</th>
        	<th>Type</th>
        	<th>Action</th>
        </tr>
        <?php
        if (! $menus->isEmpty()) {
        	foreach($menus as $row) {
        		echo "<tr>";
        			echo "<td>$row->title</td>";
        			echo "<td>$row->tag</td>";
        			echo "<td>$row->type</td>";
        			echo "<td>

					<a href='".admin_url('menus/'.$row->id.'/manage')."' class='btn btn-info btn-xs'><i class='fa fa-link'></i> Manage Links</a>
        			<a href='".admin_url('menus/'.$row->id.'/edit')."' class='btn btn-warning btn-xs'><i class='fa fa-edit'></i> Edit</a>

        			<a class='btn btn-xs btn-danger' href='#' data-toggle='modal' data-target='#confirm_" .$row->id ."'><i class='fa fa-remove'></i> Delete</a>

        			</td>";
        		echo "</tr>";
        	}
        }
        ?>
        </table>
        </div>

        <p><?=$menus->links();?></p>



    </div>
</div>

</section>


<?php
if (! $menus->isEmpty()) {
    foreach ($menus->getItems() as $menu) {
?>
<div class="modal modal-default" id="confirm_<?= $menu->id ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Menu: <?=$menu->title;?></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this menu?</p>

            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-primary pull-left col-md-3" type="button">Cancel</button>
                <form action="<?= admin_url('menus/' .$menu->id .'/destroy'); ?>" method="POST">
                    <input type="hidden" name="csrfToken" value="<?= $csrfToken; ?>" />
                    <input type="submit" name="button" class="btn btn btn-danger pull-right" value="Delete">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<?php
    }
}
