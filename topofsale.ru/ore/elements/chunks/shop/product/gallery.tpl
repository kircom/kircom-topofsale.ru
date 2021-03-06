<div class="product-view" id="msGallery">
    {if $files?}
        <div class="fotorama"
             data-nav="thumbs"
             data-thumbheight="107"
             data-thumbwidth="151"
             data-allowfullscreen="true"
             data-swipe="true"
             data-width="450"
             data-height="480"
             data-autoplay="5000">
            {foreach $files as $file}
                <a href="{$file['url']}" target="_blank">
                    <img src="{$file['card']}" alt="" title="">
                </a>
            {/foreach}
        </div>
    {else}
        <img src="{('assets_url' | option) ~ 'components/minishop2/img/web/ms2_medium.png'}"
             srcset="{('assets_url' | option) ~ 'components/minishop2/img/web/ms2_medium@2x.png'} 2x"
             alt="" title=""/>
    {/if}
</div>