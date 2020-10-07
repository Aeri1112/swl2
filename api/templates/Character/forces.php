<?php
if($skills->rfp > 0)
{
    echo "<div class='text-center'> Du hast noch ".$skills->rfp." Punkt(e) zu verteilen</div>";
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fspee"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fjump"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fpush"
                    ]);
                    ?>
                </li>
            </div>
            <div class="col-md-6">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fpull"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fseei"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fsabe"
                    ]);
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fpers"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fblin"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fheal"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fprot"
                    ]);
                    ?>
                </li>
            </div>
            <div class="col-md-6">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fproj"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fconf"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fteam"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fabso"
                    ]);
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fthro"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fgrip"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fthun"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fdest"
                    ]);
                    ?>
                </li>
            </div>
            <div class="col-md-6">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "frage"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fdrai"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fchai"
                    ]);
                    ?>
                </li>
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "fdead"
                    ]);
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <ul class="list-unstyled">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "frvtl"
                    ]);
                    ?>
                </li>
            </div>
            <div class="col-md-6">
                <li class="media">
                    <?php
                    echo $this->element('force', [
                        "force" => "ftnrg"
                    ]);
                    ?>
                </li>
            </ul>
        </div>
    </div>
</div>