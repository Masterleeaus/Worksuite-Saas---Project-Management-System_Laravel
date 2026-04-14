        function nl2br(text) {
            return text.replace(/(?:\r\n|\r|\n)/g, '<br>');
        }

        function exportWordDocrsponse(id) {
            var baseUrl =$('#route_url').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:  baseUrl + '/aidocument/exportresponsecontent',
                method: "POST",
                data: {
                    "_token": token,
                    "response_id": id
                },
                success: function(data) {
                    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                        "xmlns='http://www.w3.org/TR/REC-html40'>" +
                        "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";

                    // Assuming nl2br is a function that converts newlines to <br> tags
                    var formatted_text = nl2br(data);

                    var footer = "</body></html>";
                    var sourceHTML = header + formatted_text + footer;

                    // Create a Blob
                    var blob = new Blob([sourceHTML], {
                        type: 'application/msword'
                    });

                    // Create a download link
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'document.doc';

                    // Trigger the download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    toastrs('success', 'Word document was created successfully', 'success');
                }
            });
        }

        function exportWordDocallresponse(id) {
            var baseUrl =$('#route_url').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: baseUrl + '/aidocument/exportallresponsecontent',
                method: "POST",
                data: {
                    "_token": token,
                    "history_prompt_id": id
                },
                success: function(data) {
                    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                        "xmlns='http://www.w3.org/TR/REC-html40'>" +
                        "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";

                    // Assuming nl2br is a function that converts newlines to <br> tags
                    var formatted_text = nl2br(data);

                    var footer = "</body></html>";
                    var sourceHTML = header + formatted_text + footer;

                    // Create a Blob
                    var blob = new Blob([sourceHTML], {
                        type: 'application/msword'
                    });

                    // Create a download link
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'document.doc';

                    // Trigger the download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    toastrs('success', 'Word document was created successfully', 'success');
                }
            });
        }