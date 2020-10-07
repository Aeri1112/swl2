<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div class="w-50">
        <?= $this->Flash->render() ?>
    </div>
    <?php if(isset($stop) && $stop == "yes")
    {
    ?>
    <div class="table-responsive w-50">
        <table class="table table-sm table-striped">
        <?= $this->Form->create() ?>
            <thead class="thead-dark">
                <tr>
                    <th colspan="2">Charakter erstellen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Charactername</td>
                    <td><input type="text" name="s_charname1" class="form-control" value="<?= $_SESSION['s_charname'] ?>"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Regeln</td>
                </tr>
                <tr>
                    <td>Geschlecht</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_sex1" id="m" value="m" <?php if (isset($_SESSION['s_sex']) && $_SESSION['s_sex'] == "m") { echo " checked"; } ?> required="required" data-validity-message="This field cannot be left empty" oninvalid="this.setCustomValidity(''); if (!this.value) this.setCustomValidity(this.dataset.validityMessage)" oninput="this.setCustomValidity('')">
                            <label class="form-check-label" for="m">männlich</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_sex1" id="f" value="f" <?php if (isset($_SESSION["s_sex"]) && $_SESSION['s_sex'] == "f") { echo " checked"; } ?>>
                            <label class="form-check-label" for="f">weiblich</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Gesinnung</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_side1" id="l" value="l" <?php if (isset($_SESSION["s_side"]) && $_SESSION['s_side'] == "l") { echo " checked"; } ?> required="required" data-validity-message="This field cannot be left empty" oninvalid="this.setCustomValidity(''); if (!this.value) this.setCustomValidity(this.dataset.validityMessage)" oninput="this.setCustomValidity('')">
                            <label class="form-check-label" for="l">hell</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_side1" id="d" value="d" <?php if (isset($_SESSION["s_side"]) && $_SESSION['s_side'] == "d") { echo " checked"; } ?>>
                            <label class="form-check-label" for="d">dunkel</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Spezies</td>
                    <td><?= $_SESSION["s_race"] ?></td>
                </tr>
                <tr>
                    <td>Alter</td>
                    <td><?= $_SESSION["s_age"] ?> years</td>
                </tr>
                <tr>
                    <td>Größe</td>
                    <td><?= $_SESSION["s_height"] ?> cm</td>
                </tr>
                <tr>
                    <td>Heimatwelt</td>
                    <td><?= $_SESSION["s_homeworld"] ?></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" class="btn btn-primary" name="process" id="process" value="ok">
                    </td>
                    <td>
                        <input type="submit" class="btn btn-primary" name="shuffle" id="mischen" value="mischen">
                    </td>
                </tr>
            </tbody>
        </form>
        </table>
    </div>
    <?php
    }
    ?>
</body>
</html>