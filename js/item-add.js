(function ($) {
	$(function () {
		$("#add-item").on("click", function () {
			var
				elemInputData,
				elemInputName,
				elemTr;

			if ($("#add-data").val() != "" && $("#add-name").val() != "") {
				elemInputData = $("<input>").attr({"type": "text", "name": "data[]"}).addClass("regular-text code").val($("#add-data").val());
				elemInputName = $("<input>").attr({"type": "text", "name": "name[]"}).addClass("regular-text code").val($("#add-name").val());
				elemTr = $("<tr>").append($("<td>").append(elemInputData)).append($("<td>").append(elemInputName));

				$("#add-table").append(elemTr);
				$("#add-data").val("");
				$("#add-name").val("");
			}
		});
	});
})(jQuery);