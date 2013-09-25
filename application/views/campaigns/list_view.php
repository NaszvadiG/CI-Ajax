<div class="folder-content">
    <div class="form-horizontal">
        <p id="p_notification" class="msg" style="display: block;" align="center"></p>
        <div class="control-group">
            <span class="control-left">Có <b><?=$total?></b> campaign</span>
<span></span>
</div><!--control-group-->
<table id="tbl-list" border="0" cellpadding="0" cellspacing="0" class="table table-bordered table-hover">
    <tbody>
    <tr class="info">
        <td width="5%"><input type="checkbox" class="input_checkbox_cls"></td>
        <td width="7%">Chi tiết</td>
        <td width="7%">Campaign ID</td>
        <td width="23%">Campaign name</td>
        <td width="5%">Type</td>
        <td width="15%">Daily budget</td>
        <td width="15%">Budget</td>
        <td width="13%">Từ ngày</td>
        <td width="13%">Đến ngày</td>
        <td width="5%">Trạng thái</td>
        <td width="10%">Sửa</td>
    </tr>
    <?php
    if(count($info)>0):
        $i=0;
        foreach($info as $rs):?>
            <?php
                switch ($rs->status){
                    case 'started':
                        $class = 'btn-info';
                        break;
                    case 'running':
                        $class = 'btn-success';
                        break;
                    case 'paused':
                        $class = 'btn-warning';
                        break;
                    case 'banned':
                        $class = 'btn-danger';
                        break;
                    case 'new':
                        $class = 'btn-inverse';
                        break;
                }
            ?>
            <tr class="user-<?=$rs->_id;?>" align="center">
                <td><input type="checkbox" class="select_cb" id="<?=$rs->_id;?>"></td>
                <td>
                    <a class="popovertip" title="<b>Chi tiết <?=$rs->name;?> campaign</b>" data-content="
                            <?php if(count($profile[$i])>0): foreach($profile[$i] as $re):?>
                            <b>Profile:</b> <?=$re->name;?><br/>
                            <?php endforeach;else:?>
                            <b>Chưa có profile nào</a>
                            <?php endif;?>
                            ">Chi tiết</a>
                </td>
                <td class="name" name=""><?=$rs->_id;?></td>
                <td class="name" name="<?=$rs->status;?>"><?=$rs->name;?></td>
                <td><?php echo $rs->ads_type;?></td>
                <td><?php echo number_format($rs->daily_budget,2);?></td>
                <td><?php echo number_format($rs->budget,2);?></td>
                <td><?php if(isset($rs->startdate)) echo date('Y-m-d',$rs->startdate);?></td>
                <td class="status_td"><?php if(isset($rs->enddate)) echo date('Y-m-d',$rs->enddate);?></td>
                <td class="find_status">
                    <div class="btn-group">
                        <button class="btn <?=$class;?> dropdown-toggle btn-small" data-toggle="dropdown">
                            <?=$rs->status;?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right change-status" status="<?=$rs->status;?>" for="<?=$rs->_id;?>">
                            <li><a for="paused" onclick="campaigns.changeStatus('<?=$rs->_id;?>','<?=$rs->status;?>','paused');">Stop</a></li>
                            <li><a for="started" onclick="campaigns.changeStatus('<?=$rs->_id;?>','<?=$rs->status;?>','started');">Start</a></li>
                            <li><a for="running" onclick="campaigns.changeStatus('<?=$rs->_id;?>','<?=$rs->status;?>','running');">Run</a></li>
                            <li><a for="banned" onclick="campaigns.changeStatus('<?=$rs->_id;?>','<?=$rs->status;?>','banned');">Banned</a></li>
                            <li><a for="delete" onclick="campaigns.changeStatus('<?=$rs->_id;?>','<?=$rs->status;?>','delete');">Delete</a></li>
                        </ul>
                    </div>
                <!--<td><a href="<?/*=base_url()*/?>backend/users/edit/<?/*=$rs->_id;*/?>">Sửa</a></td>-->
                <td><span class="btn btn-info" onclick="campaigns.edit('<?=$rs->_id;?>')">Sửa</span></td>
            </tr>
            <?php $i++;
        endforeach; else:
        ?>

    <?php endif;?>
</table>
        <?=$page_nav;?>
</div><!--form-horizontal-->
</div><!--form-horizontal-->
<!-- Modal -->

<div id="myModalsearch" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top:50%; left:45%;
width: 680px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Search campaign <span class="campaign_name_span"></span> </h3>
    </div>
    <div class="modal-body">
        <table>
            <tr>
                <td>Campaign name:</td>
                <td>
                    <input name="campaign-name-txt" id="campaign-name-txt" value="" >
                </td>
                <td>Type:</td>
                <td>
                    <select name="campaign-type-search">
                        <option value=''>Chọn</option>
                        <option value='cpim'>Cost Per Impression</option>
                        <option value='cpc'>Cost Per Click</option>
                        <option value='cpv'>Cost Per View</option>
                        <option value='cpd'>Cost Per Download</option>
                        <option value='cpi'>Cost Per Install</option>
                        <option value='cpa'>Cost Per Active</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>From date:</td>
                <td>
                    <input name="from-date-txt" id="from-date-txt" value="">
                </td>
                <td>To date:</td>
                <td>
                    <input name="end-date-txt" id="end-date-txt" value="">
                </td>
            </tr>
            <tr>
                <td>Budget Min:</td>
                <td>
                    <input name="budget-min-txt" id="budget-min-txt" value="">
                </td>
                <td>Budget Max:</td>
                <td>
                    <input name="budget-max-txt" id="budget-max-txt" value="">
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary search_campaign">Seach campaign</button>
    </div>
</div>