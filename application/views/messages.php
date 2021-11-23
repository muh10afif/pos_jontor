<?php if($this->session->has_userdata('success')) { ?>
<div class="alert alert-success alert-dismissable">
	<i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success'); ?>
</div>
<?php } elseif($this->session->has_userdata('danger')) { ?>
<div class="alert alert-danger alert-dismissable">
	<i class="icon fa fa-ban"></i> <?php echo strip_tags(str_replace('<p>', '',$this->session->flashdata('danger').'</p>')); ?>
</div>
<?php } ?>