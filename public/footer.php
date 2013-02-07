</div>
	<div class="footer">
		<p><small>Fat-Free Framework is licensed under the terms of the GPL v3<br />
		Copyright &copy; 2009-2011 F3::Factory</small></p>
		<pre><?php echo Base::instance()->format('Page rendered in {0} msecs / Memory usage {1} Kibytes',round(1e3*(microtime(TRUE)-$TIME),2),round(memory_get_usage(TRUE)/1e3,1)); ?></pre>
	</div>
</body>
</html>