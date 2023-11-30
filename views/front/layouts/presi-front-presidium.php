<!--Here Goes HTML-->
<style>

.presi-wrapper {
    margin: 0;
    box-sizing: border-box;
}

.presi-wrapper .presi-items {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.presi-items .presi-item {
    flex: 1;
    margin: 5px;
    max-width: 400px;
    min-width: 300px;

}

.presi-item .presi-portfolio {
    display: flex;
    margin: 5px 0px;
}

.presi-portfolio .presi-main {
    flex: 1;
    width: 70%;
    display: flex;
    flex-direction: column;
    align-content: flex-start;
    justify-content: flex-start;
}

.presi-main .presi-portfolio-title{
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    align-content: center;
    width: 100%;
    background: darkgray;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 16px;
    line-height: 1.5em;
    text-align: center;
    font-weight: 600;
    color: #FFF;
    border-radius: 5px;
}
.presi-portfolio-title span{
    padding-bottom: 3px;
}

.presi-main .presi-portfolio-email,
.presi-main .presi-portfolio-phone {
    flex: 1;
    display: flex;
    align-items: center;
    width: 100%;
    background: white;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 14px;
    line-height: 1.5em;
}

.presi-portfolio .presi-image {
    flex: 1;
    max-width: 35%;
 }

 .presi-image img{
    max-width: 100%;
 }

.presi-item .presi-header,
.presi-item .presi-footer,
.presi-item .presi-main,
.presi-item .presi-image {
    padding: 5px;
}

.presi-item .presi-header {
    user-select: none;
    background-color: #6001d2;
    font-family: 'Rubik', sans-serif;
    font-size: 18px;
    color: #FFF;
    line-height: 25px;
    box-sizing: border-box;
    font-weight: 600;
    text-align: center;
}

.presi-item .presi-footer {
    background-color: #121026;
    font-family: 'Rubik', sans-serif;
    font-size: 16px;
    color: #FFF;
    line-height: 25px;
    box-sizing: border-box;
    font-weight: 400;
    text-align: center;
    user-select: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>

<body>
    <div class="presi-wrapper">
        <div class="presi-items">
            <?php foreach($presi_portfolios as $presi_portfolio): ?>
                <div class="presi-item">
                    <div class="presi-header">
                        <?php echo $presi_portfolio->name?>
                    </div>
                    <div class="presi-portfolio">
                        <div class="presi-image">
                            <img src="<?php echo $presi_portfolio->pic->src?>">
                        </div>
                        <div class="presi-main">
                            <div class="presi-portfolio-title"><span><?php echo $presi_portfolio->title?></span></div>
                            <div class="presi-portfolio-email"><span><?php echo $presi_portfolio->e_mail?></span></div>
                            <div class="presi-portfolio-phone"><span><?php echo $presi_portfolio->phone?></span></div>
                        </div>
                    </div>
                    <div class="presi-footer">
                        Подробнее
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>