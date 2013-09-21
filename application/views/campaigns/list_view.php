<div class="folder-content">
    <div class="form-horizontal">
        <div class="control-group">
            <a class="btn btn-active-user" href="javascript:void(0);" id="active_user_btn">Kích hoạt campaign</a>
            <a class="btn btn-active-user" href="javascript:void(0);" id="running_user_btn">Running campaign</a>
            <a class="btn btn-active-user" href="javascript:void(0);" id="stop_user_btn">Stop campaign</a>
            <a class="btn btn-banned-user" href="javascript:void(0);" id="banned_user_btn">Banned campaign</a>
            <a class="btn btn-banned-user" href="javascript:void(0);" id="del_user_btn">Delete campaign</a>
            <a href="#myModalsearch" role="button" data-toggle="modal" class="btn">Tìm kiếm</a>
            <a class="btn" href="<?=base_url('backend/home')?>">Quay về trang chính</a>
        </div><!--control-group-->
        <hr />
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
                <td class="find_status"><?=$rs->status;?></td>
                <!--<td><a href="<?/*=base_url()*/?>backend/users/edit/<?/*=$rs->_id;*/?>">Sửa</a></td>-->
                <td><a href="#myModal" role="button" data-toggle="modal" class="a_edit" id="<?=$rs->_id;?>">Sửa</a></td>
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