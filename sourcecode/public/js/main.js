function saveSuccess(strFieldKeyName, strId) {
    $('#form_save_result').html("Save successful!");
    $('#' + strFieldKeyName).val(strId);
    $('#form_type').html("Edit Record");
    $('#type').val("Edit");
    $('#form_save_result').attr("class", "form_save_success");
    $('#form_save_result').fadeIn('slow', function() {
        setTimeout("$('#form_save_result').fadeOut('slow')", 2000);
    });
}

function editSuccess() {
    $('#form_save_result').html("Save successful!");
    $('#form_save_result').attr("class", "form_save_success");
    $('#form_save_result').fadeIn('slow', function() {
        setTimeout("$('#form_save_result').fadeOut('slow')", 2000);
    });
}

function saveFailed(strMessage) {
    $('#form_save_result').attr("class", "form_save_failed");
    $('#form_save_result').html(strMessage);
    $('#form_save_result').fadeIn('slow', function() {
        setTimeout("$('#form_save_result').fadeOut('slow')", 2000);
    });
}

function disableField(strFieldName) {
    var field = $('#' + strFieldName);
    if (field.is("select")) {
        $('#' + strFieldName).attr("onfocus", "this.defaultIndex=this.selectedIndex;");
        $('#' + strFieldName).attr("onchange", "this.selectedIndex=this.defaultIndex;");
    } else  {
        $('#' + strFieldName).attr("readonly", "readonly");
    }
    $('#' + strFieldName).removeClass("text").addClass("readonly text");
}
