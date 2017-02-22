
    <?php
    $scoreArray = $instance->scoreArray;
    $actionPage = isset($teamActionPage) && $teamActionPage == true ? "/teams/edit.php" : "/clients/edit.php";

    for ($i = 0; $i < count($scoreArray); $i++){
        $item = $scoreArray[$i];

        ?>
        <div class="card">
            <div class="card-block">
                <h4 class="card-title">Очки "<?= $item->gameName ?>"</h4>
                <div class="card-text">
                    <p>
                        <form class="from" method="post" action="<?= $actionPage ?>" >
                            <input type="hidden" name="actionPerformed" value="scoreInput">
                            <input type="hidden" name="scoreId" value="<?= $item->id ?>">
                            <input type="hidden" name="clientId" value="<?= $item->clientId ?>">
                            <input type="hidden" name="gameName" value="<?= $item->gameName ?>">
                            <input type="hidden" name="currentScore" value="<?= $item->value ?>">


                            <div class="form-group">
                                <label class="col-sm-3" for="scoreAddition"><span class="scoreValue" id="scoreValue<?=$i?>"><?= $item->value ?> </span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button id="scoreMinus<?=$i ?>" class="btn btn-danger" type="button"><i class="fa fa-minus"></i></button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button id="scorePlus<?=$i ?>" class="btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                        </span>
                                        <input type="number" class="form-control text-sm-center" id="scoreAddition<?=$i ?>" name="scoreAddition" value="0" placeholder="Введите значение" required>

                                        <span class="input-group-btn">
                                            <button type="submit" id="submit-btn" class="btn btn-primary submit-btn">Сохранить</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <hr>
                    </p>

            </div>
        </div>
    </div>

        <script>
            $('#scoreMinus<?=$i ?>').on("click", function(){
                setValues<?=$i ?>(-1);
            });


            $('#scorePlus<?=$i ?>').on("click", function(){
                setValues<?=$i ?>(1);
            });

            $('#scoreAddition<?=$i ?>').keyup(function(){
                setValues<?=$i ?>();
            });

            function setValues<?=$i ?>(additionPoint){

                additionPoint = typeof additionPoint !== 'undefined' ? additionPoint : 0;
                var source = $('#scoreAddition<?=$i ?>').val();
                if (source == "" && source != "-") {
                    source = 0;
                    $('#scoreValue<?=$i ?>').text(scores);
                }
                var addValue = parseInt(source) + additionPoint;

                var result = <?=$item->value ?> + addValue;
                $('#scoreValue<?=$i ?>').text(result);
                $('#scoreAddition<?=$i ?>').val(addValue);
            }
        </script>

    <?php }  ?>


    <script>

        $('.form').submit(function(){
            submitBtn.prop('disabled', true);
        });
    </script>