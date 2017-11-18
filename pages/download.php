<?php if(checkFeatureElement(FE_Download_Enteris)){ ?>
<table width="100%">
	<tr>
		<td align="center" colspan="3">
			<a href="software/windows/Enteris.exe"  target="_blank"><img src="images/download.png" width="200px" /> </a>
		</td>
	</tr>
	<tr>
		<td style="width:30%"></td>
		<td style="width:40%">
			<h4 align="center">System Requirements:</h4>
			<style>
				#requirements > li{
					font-size: 11px
				}
			</style>
			<ul id="requirements">
				<li>Windows 7, 8, 10, 32/64-bits</li>
				<li>Java Virtual Machine 1.7</li>
				<li>10 GBs of disk space for recordings and downloading of large audio/video files.</li>
				<li>Microphone for recording audio/videos and desktop screen.</li>
				<li>Permissions in anti-virus softwares and firewalls.</li>
			</ul>
		</td>
		<td style="width:30%"></td>
	</tr>
</table>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>