
<div class="container-fluid">

    <script src="<?php echo base_url(); ?>assets/js/loadingAnswers_Questions/LoadA_Q.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/progressbar.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

    <div style="" data-placement="bottom" data-toggle="popover" title="" data-content="<?= lang('r_question_help') ?>" data-container="body" class="popup panel row">

        <div class="col-12">
            <br>
            <div class="container-fluid" id="jumbotron_question">
                <div class="container-fluid">
                    <p class="tlScale" id="question_text">

                    </p><hr><br>
                </div>
            </div>
        </div>

        <div class="row"  id="jumbotron_answer">
            <div class="visible-md visible-sm visible-lg col-sm-1"></div>

            <?php
                $emotion_index = 0;
                foreach ($options as $option) {
            ?>
                    <div class=" col-sm-offset-0 col-sm-2 col-md-offset-0 col-md-2 col-xs-5">
                        <div style="width:100%;text-align: center;margin:auto;">

                            <button style="width:100%;height:100%;" class="shover btn btn-fab answerbutton btn-default" id="button_emotion<?= $emotion_index ?>" 
                                    onclick="(storeAnswer(<?php echo $emotion_index ?>, ' <?php echo base_url() ?>', '{category}'));"
                                    value="<?php echo htmlspecialchars($option->option) ?>">

                                <img style="width:100%;height:100%;" src=<?php echo base_url() . 'assets/imgs/emotions/' . $emotion_index . ".png" ?> alt="Answer: <?= htmlspecialchars($option->option); ?>">
                            </button>
                            <br>
                            <br>
                            <?= htmlspecialchars($option->option); ?>
                            <?php
                                $emotion_index++;
                            ?>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
        <br>
        <br>
        <br>

        <div class="row container-fluid">
            <br>
            <ol id ="progress-bar" class="questions quesions--medium">
            </ol>

            <div class="col-sm-12" >
                <div class="container-fluid" >
                    <!--<div class="progress" id="progress" style="height: 35px;">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="progressBar" style="background-color: #673AB7;">
                            <span class="txOutline" id="progressbarText" style="position: absolute;display: block;width: 95%;color:black"></span>
                        </div>
                    </div>-->
                </div>
                <br>
            </div>
            <div class="pull-right" >
                <button class="btn btn-default" type="submit" name="back" value="Go back" id="GoBackButton" onclick="pressGoBack()" style=" color:#673AB7;">
                    <?= lang('r_question_back_cat') ?>
                </button>
            </div>
        </div>
    </div>
    <br>
    <br>
</div>

<script >initialize('<?php echo base_url(); ?>', '<?= lang('r_question_back') ?>', '<?= lang('r_question_back_cat') ?>', '<?= lang('r_question_completed') ?>', {numberQuestions}, "<?= lang('r_question_question') ?>",<?php echo json_encode($allUnansweredQuestions); ?>);</script>
<script>
    $(document).ready(function () {
//        $(".panel").show("slide", { direction: "left" }, 500);
    });
</script>
