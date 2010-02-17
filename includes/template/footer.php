
	<div class="footer">
		<div class="container">
			<p>Copyright &copy; 2009 - Pete Brousalis, Eric Stokes, Tim Hollingshead
			<?php
				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				$finish = $time;
				$total_time = round(($finish - $start), 4)/1000000000;
				echo '<strong>Page generated in '.round($total_time,3).' seconds.</strong>';
			?> 			
			</p>
		</div>
	</div>
</div>
</body>
</html>