<div class="form-horizontal">
    <div class="row-fluid">
        <form id="campaign-form" style="width: 550px">
            <input type="hidden" name="_id" value="<%= data?data._id.$id:'' %>"/>

            <div class="control-group">
                <label class="control-label">Tên:</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" name="name" value="<%= data?data.name:'' %>"/>
                </div>

            </div>
            <div class="control-group">
                <label class="control-label">Loại quảng cáo:</label>

                <div class="controls">
                    <select name="ads_type" class="type" id="slt_type" style="height: 34px;">
                        <option value="cpim">Cost per Impression</option>
                        <option value="cpc">Cost per Click</option>
                        <option value="cpv">Cost per View</option>
                        <option value="cpd">Cost per Download</option>
                        <option value="cpi">Cost per Install</option>
                        <option value="cpu">Cost per Use</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Ngày bắt đầu:</label>

                <div class="controls">
                    <input id="startdate" type="text" class="input-xlarge datepicker" name="start" value="<%= (data&&data.startdate!='')?hl.parseDayTime(data.startdate):'' %>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Ngày kết thúc:</label>
                <div class="controls">
                    <input id="enddate" type="text" class="input-xlarge datepicker" name="end" value="<%= (data&&data.enddate!='')?hl.parseDayTime(data.enddate):'' %>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Daily budget</label>
                <div class="controls">
                    <input input="text" class="input-xlarge" name="daily-budget-txt" id="daily-budget-txt" value="<%=(data)?data.daily_budget:''%>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Budget</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="budget-txt" value="<%=(data)?data.budget:''%>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Min cost</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="min-cost-txt" value="<%=(data)?data.min_cost:''%>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Max cost</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="max-cost-txt" value="<%=(data)?data.max_cost:''%>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Logo</label>
                <div class="controls">
                    <input type="file" class="input-xlarge" id="userfile" name="userfile" onchange="campaigns.upload('campaigns/upload','userfile');"/>
                    <span><%=(data&&data.ads_content.logo!="")?data.ads_content.logo:"" %></span>
                </div>
            </div>
            <div class="control-group" style="display: none;">
                <label class="control-label"></label>
                <div class="controls" id="show_image">

                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Link</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="link-txt" value="<%=(data)?data.ads_content.url:'' %>" />
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    .datepicker{
        z-index: 9999999;
    }
</style>