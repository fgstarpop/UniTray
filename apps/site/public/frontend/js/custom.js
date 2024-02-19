(function($){
    "use strict";

    // Header Sticky
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 120){
            $('.navbar-area').addClass("is-sticky");
        }
        else {
            $('.navbar-area').removeClass("is-sticky");
        }
    });

    $(document).on('click', '.js-delete', function () {
        var deleteUrl = $(this).data('url');

        confirmAction('Bạn có chắc chắn muốn xóa ?', function (result) {
            if (result) {
                $.ajax({
                    type: 'DELETE',
                    url: deleteUrl,
                    data: {
                        _method: "DELETE",
                        _token: token
                    },
                    success: function (res) {
                        if (res.status == 'error') {
                            showMessage('error', res.message);
                        } else {
                            showMessage('success', res.message);
                        }
                        window.setTimeout(function(){location.reload()},2000)
                    }
                })
            }
        })
    })

	// Search Menu JS
	$(".search-box i").on('click', function() {
		$(".search-overlay").toggleClass("search-overlay-active");
	});
	$(".search-overlay-close").on('click', function() {
		$(".search-overlay").removeClass("search-overlay-active");
	});

    // Button Hover JS
	$('.default-btn').on('mouseenter', function(e) {
		var parentOffset = $(this).offset(),
		relX = e.pageX - parentOffset.left,
		relY = e.pageY - parentOffset.top;
		$(this).find('span').css({top:relY, left:relX})
	}).on('mouseout', function(e) {
		var parentOffset = $(this).offset(),
		relX = e.pageX - parentOffset.left,
		relY = e.pageY - parentOffset.top;
		$(this).find('span').css({top:relY, left:relX})
	});

    // Accordion JS
	$('.accordion > li:eq(0) .title').addClass('active').next().slideDown();
	$('.accordion .title').on('click', function(j) {
		var dropDown = $(this).closest('li').find('.accordion-content');
		$(this).closest('.accordion').find('.accordion-content').not(dropDown).slideUp();
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).closest('.accordion').find('.title.active').removeClass('active');
			$(this).addClass('active');
		}
		dropDown.stop(false, true).slideToggle();
		j.preventDefault();
    });

    // Preloader JS
	jQuery(window).on('load', function() {
        jQuery(".preloader").fadeOut(500);
    });

    // Go to Top
	$(window).on('scroll', function() {
        if ($(this).scrollTop() > 0) {
            $('.go-top').addClass('active');
        } else {
            $('.go-top').removeClass('active');
        }
	});

	$(window).on('scroll', function() {
		var scrolled = $(window).scrollTop();
		if (scrolled > 600) $('.go-top').addClass('active');
		if (scrolled < 600) $('.go-top').removeClass('active');
	});

	$('.go-top').on('click', function() {
		$("html, body").animate({ scrollTop: "0" },  500);
	});

}(jQuery));

function confirmAction(text, callback) {
    bootbox.confirm({
        title: 'Xác nhận',
        message: text,
        buttons: {
            confirm: {
                label: '<i class="fal fa-check mr-2"></i> ' + 'Có',
                className: 'btn btn-primary'
            },
            cancel: {
                label: '<i class="fal fa-times mr-2"></i>' + 'KHông',
                className: 'btn btn-danger'
            }
        },
        callback: callback
    });
}
var stack_custom_top = {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1};

// Custom bottom position
function showMessage(type, message) {
    var opts = {
        title: 'Hệ thống',
        text: message,
        addclass: "bg-primary",
        stack: stack_custom_top
    };
    switch (type) {
        case 'error':
            opts.title = 'Ôi không';
            opts.addclass = "bg-white notifi-danger";
            opts.type = "error";
            break;

        case 'info':
            opts.title = "Breaking News";
            opts.addclass = "bg-white notifi-primary";
            opts.type = "info";
            break;

        case 'success':
            opts.title = 'Thành công';
            opts.addclass = "bg-white notifi-success";
            opts.type = "success";
            break;
    }
    new PNotify(opts);
}
