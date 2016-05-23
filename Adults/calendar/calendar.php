<div class="row">
      <div class="col-lg-12">
              <h3 class="page-header"><i class="fa fa-laptop"></i>Calendrier</h3>
              <ol class="breadcrumb">
                      <li><i class="fa fa-home"></i><a href="accueil_adultes.php?page=welcome_adult">Accueil</a></li>
                      <li><i class="fa fa-laptop"></i>Calendrier</li>						  	
              </ol>
      </div>
</div>



<!-- Full calendar-->
 <div id='calendar'></div>       
    
 <!-- Clickable event Full calendar : display window-->
<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog" style="left: 0px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>