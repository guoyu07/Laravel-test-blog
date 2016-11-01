<?=with(new App\Http\Pagination\CustomerPresenter($data_page))->render()?>
<script>
(function(){
	$('.page_nav a').each(function () {
	    $this = $(this);
		$this.data('href',$this.attr('href'));
		$this.attr('href','javascript:;');
		$this.click(function(){
            $(this).post($(this).data('href'),function(rsp){
                $this.closest('.result_wrap').replaceWith(rsp);
			});
		});
	})
})();
</script>
