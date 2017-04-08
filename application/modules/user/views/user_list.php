<!-- Korisnici aplikacije - lista - 09.03.2017. -->
<div class="content-wrapper">
<section class="content">
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Korisnici aplikacije</h3>
            <div class="box-tools">
              <?php if(CheckPermission("user", "own_create")){ ?>
                <button type="button" class="btn-sm  btn btn-success modalButtonUser" data-toggle="modal"><i class="glyphicon glyphicon-plus" ></i> Dodaj novi</button>
                <?php } ?>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="cell-border example1 table table-striped table1 table-bordered table-hover dataTable">
                <thead>
                  <tr>
                    <th><input type="checkbox" class="selAll"></th>
                    <th width="30%">E-mail adresa</th>
                    <th width="30%">Korisničko ime</th>
                    <th width="30%">Status</th>
                    <th width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<div class="modal fade" id="nameModal_user" role="dialog">
  <div class="modal-dialog">
    <div class="box box-primary popup" >
      <div class="box-header with-border formsize">
        <h3 class="box-title">Korisnici aplikacije</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body" style="padding: 0px 0px 0px 0px;"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  var url='<?php echo base_url();?>';
  var table=$('#example1').DataTable({
    dom: 'lfBrtip',
    buttons: [],
    "processing": true,
    "serverSide": true,
    "ajax": url+"user/dataTable",
    "sPaginationType": "full_numbers",
    "language": {
      "url": "<?php echo base_url()."assets/js/Croatian.json";?>",
      "search": "_INPUT_",
      "searchPlaceholder": "Pretraži",
      "paginate": {
        "next": '<i class="fa fa-angle-right"></i>',
        "previous": '<i class="fa fa-angle-left"></i>',
        "first": '<i class="fa fa-angle-double-left"></i>',
        "last": '<i class="fa fa-angle-double-right"></i>'
      }
    },
    "iDisplayLength": 10,
    "aLengthMenu": [[10, 25, 50, 100,500,-1], [10, 25, 50,100,500,"Svi"]]
  });
  setTimeout(function() {
    var add_width=$('.dataTables_filter').width()+$('.box-body .dt-buttons').width()+10;
    $('.table-date-range').css('right',add_width+'px');
  }, 300);
  $("button.closeTest, button.close").on("click", function (){});
});
</script>
