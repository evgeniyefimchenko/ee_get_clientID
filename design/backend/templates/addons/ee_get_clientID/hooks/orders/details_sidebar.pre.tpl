<div id="ee_user_id" style="padding-top: 15px; display: none;"><strong><p>user_id = {$order_info.user_id}</p></strong></div>
<div id="ee_user_ya_id" style="padding-top: 15px; display: none;"><strong><p>clientID = {$order_info.user_id|fn_ee_get_clientID_get_ID}</p></strong></div>
{literal}
	<script>
		$('.profile-info').append($('#ee_user_id')).attr('style', 'margin-bottom: 2px;');
		$('.profile-info').append($('#ee_user_ya_id')).attr('style', 'margin-bottom: 2px;');
		$('#ee_user_id, #ee_user_ya_id').show();
	</script>
{/literal}