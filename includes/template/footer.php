
	<div class="footer">
		<div style="margin: 0 auto; width: 950px;">
			<p>Copyright &copy; 2009 - Pete Brousalis, Eric Stokes, Tim Hollingshead
			<?php
				/* TODO: Random beer! */
				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				$finish = $time;
				$total_time = round(($finish - $start), 4)/1000000000;
				echo '<strong>Page generated in '.$total_time.' seconds.</strong>';
			?> 			
			</p>
		</div>
	</div>
</div>
</body>
</html>