<?php ?>


  <div class="ui two wide column"></div>
  <div class="twelve wide column">
    <div class="ui two column aligned stackable divided grid">
      <div class="column">
<!--        <h1>UK Signature Creator</h1>-->
<!--        <p>Please input into these fields</p>-->
        <div id="form" class="ui form left">
          <!-- // JS added fields -->
        </div>

        <button type="button" id="build-button" class="ui uky-button" onclick="buidSignature()">Build My Full Signature</button>
          <br/>
          <button type="button" id="build-button" class="ui uky-button" onclick="buidSignature('simplified')">Build My Simple Signature</button>
            <span>(For email software that doesn't support the full signature)</span>
      </div>
      <div class="column">
        <div id="demo" class="left"></div>
        <div id="directions" class="left" style="display: none;">

            <button id="copy-button" class="uky-button copy-btn" type="button"   style="display: none;">
              Copy HTML version to clipboard!
            </button>
            <a id="download-file-button" class="uky-button" type="button" href="#" style="display: none;">Download HTML File</a>

            <?php  $blocks = block_get_blocks_by_region('email_instructions');
            print render($blocks); ?>
            <!--<p>Outlook Signature tips:</p>-->
          <!--<ul>-->
          <!--  <li><a href="./assets/tutorial/edit-signatures-outlook-2016.png" target="_blank">Outlook 2016 screenshot</a></li>-->
          <!--  <li><a href="./assets/tutorial/add-file-signature-outlook-2016.mp4" target="_blank">Outlook 2016 video</a></p></li>-->
          <!--  <li><a href="//youtu.be/0qvJzNeL0tk?t=12s" target="_blank">Outlook 2010 video</a></p></li>-->
          <!--</ul>-->
        </div>
        <br>
      </div>
    </div>



  </div>
<!--</div>-->

<!-- update hidden input for ZeroClipboard -->
<input type="text" id="zc-input" value="this would be really nice to copy" style="display: none;" />
