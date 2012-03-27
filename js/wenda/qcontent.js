KindEditor.ready(function(K) {
	var editor1 = K.create('textarea[name="content"]',
			{
				cssPath : '/js/kindeditor/plugins/code/prettify.css',
				fileManagerJson : '../php/file_manager_json.php',
				resizeType : 1,
				shadowMode : false,
				allowUpload : true,
				items : [ 'bold', 'italic', 'underline', 'strikethrough',
						'removeformat', '|', 'insertorderedlist',
						'insertunorderedlist', '|', 'textcolor', 'bgcolor',
						'fontname', 'fontsize', '|', 'link', 'unlink',
						'emoticons', 'code', 'image', 'flash', 'quote', '|',
						'selectall', 'source', 'about' ]
			});
});

function ajax_post(the_url, the_param, succ_callback) {
	$.ajax({
		type : 'POST',
		cache : false,
		url : the_url,
		data : the_param,
		success : succ_callback,
		error : function(html) {
			alert("提交数据失败，代码:" + html.status + "，请稍候再试");
		}
	});
}

function reply_to_post(url) {
	ajax_post(url, "", function(data) {
		$.fancybox(data);
	});
}

function post_reply(url) {
	var b = $('#rp_content').val();
	if (b.length > 200) {
		alert("评论适合简短内容，你的内容超过200个字，请使用‘引用此回贴’");
		return false;
	}

	if (b.length == 0) {
		if (confirm('确定要取消留言发送吗？')) {
			$.fancybox.close();
			return true;
		} else {
			return false;
		}
	}

	$('#bt_submit_new_reply').val('正在发表评论...');
	$('#bt_submit_new_reply').attr('disabled', 'disabled');

	var form = $('#frm_q_detail').serialize();
	var parent_id = $('#parent_id').val();

	ajax_post(
			url,
			form,
			function(html) {
				var json = eval('(' + html + ')');
				if (json.error > 0) {
					$('#s_error_msg').hide();
					$('#s_error_msg').html(json.msg);
					$('#s_error_msg').show("fast");
					$('#bt_submit_new_reply').removeAttr('disabled');
				} else {
					$('#s_error_msg').hide();
					$('#frm_q_detail').hide();
					$('#s_success_msg')
							.html("评论发表成功，<a href='#' onclick='$.fancybox.close();return false;'>关闭此窗口</a>");
					$('#s_success_msg').fadeIn();
					t = setTimeout(function() {
						clearTimeout(t);
						$.fancybox.close();
						var url = json.url;
						ajax_post(url, "", function(html) {
							$('#post_answer_' + parent_id).html(html);
						});
					}, 800);
				}
			});
}

function closeit() {
	if ($('#rp_content').val().length == 0 || confirm("确定要取消留言发送吗？")) {
		$.fancybox.close();
	}
}

$(document).ready(function() {
	// select category for qutestion type
	$('.qa_type').click(function() {
		$('a.qa_type').removeClass('selected');
		$(this).addClass('selected');
		$('#category_id').val($(this).attr('type'));
		return false;
	});
});
