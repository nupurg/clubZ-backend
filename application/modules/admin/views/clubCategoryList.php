<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Club Categories
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Club Categories</li>
      </ol>
      <button type="button" class="btn bg-olive btn-flat margin pull-right" id="addBtn">Add Club Category</button>
        <?php if($this->session->flashdata('success') != null) : ?> <!-- for Delete -->
               
                <div class="csAlert">
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>  <i class="icon fa fa-check"></i> Success!</h4>
                        <?php  echo $this->session->flashdata('success');?> 
                      
                    </div>
                </div><!-- /.box-body -->
                  <script>
              setTimeout(function() {
              $('.alert-success').fadeOut('fast');
              }, 1000);
              </script> 
       <?php endif; ?>
 
       <?php if($this->session->flashdata('error') != null) : ?>  <!-- for Delete -->
               
                <div class="csAlert">
                    <div class="alert alert-danger" id="danger-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>  <i class="icon fa fa-check"></i> oops!</h4>
                        <?php  echo $this->session->flashdata('error');?>
                    </div>
                </div><!-- /.box-body -->
                 <script>
              setTimeout(function() {
              $('.alert-danger').fadeOut('fast');
              }, 1000);
              </script>
      <?php endif; ?>

    </section>
     

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->

          <div class="box">
            <div class="pull-right col-md-3 noMargin">              
                    
               </div>
                <div class="pull-right div-select col-md-3 noMargin">
                 
                </div>
            <!-- /.box-header -->
            <div class="box-body">
               

              <table id="clubCategoryList" class="table table-bordered table-striped">
                <thead>
                <th>S.No.</th>
                <th>Club Category Name</th>
                <th>status</th>
                <th style="width: 12%">Action</th>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
                
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <div id="addModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('admin/club/addClubCategory') ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Club Category</h4>
                </div>
                <div class="modal-body">
                    
                   <!--  <div class="alert alert-danger" id="error-box" style="display: none"></div> -->
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="clubCategoryName" id="clubCategoryName" placeholder=" Club Category Name" maxlength="30"/>
                                    </div>
                                   
                                </div>
                            </div>
                      
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="<?php echo THEME_BUTTON;?>" >Add</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


  <div id="editModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('admin/club/updateClubCategory') ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Club Category</h4>
                </div>
                <div class="modal-body">
                    
                   <!--  <div class="alert alert-danger" id="error-box" style="display: none"></div> -->
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="clubCategoryName" id="clubCategoryNm" placeholder=" Club Category Name" maxlength="30"/>
                                        <input type="hidden" name="clubCategoryId" id="clubCategoryId">
                                    </div>
                                   
                                </div>
                            </div>
                      
                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="<?php echo THEME_BUTTON;?>" >Update</button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
