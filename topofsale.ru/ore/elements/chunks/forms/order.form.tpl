<!-- modal -->
<div id="order" class="popup" data-popup-name="gp-popup">
    <div class="popup-content">
        <header class="popup-header">
            <button class="popup__close" data-popup="close">
                <span class="icon-close">Close</span>
            </button>
            <h3 class="popup__title"></h3>
        </header>
        <form action="" method="post" class="ajax_form form popup-body">
            <div class="form-group">
                <input type="text" name="name" placeholder="{'lw.form-name'|lexicon}" class="form-input">
            </div>
            <div class="form-group">
                <input type="text" name="phone" placeholder="{'lw.form-phone'|lexicon}" class="form-input">
            </div>
            <div class="form-group">
                <input type="hidden" name="product" value="" class="form-input">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn_form">{'lw.callme-back'|lexicon}</button>
            </div>
        </form>
        <footer class="popup-footer">
            <p>{'lw.politics-disclaimer'|lexicon}</p>
            <p><a href="{'29'|url}">{'lw.politics-link'|lexicon}</a>
            </p>
        </footer>
    </div>
    <div class="popup-overlay"></div>
</div>