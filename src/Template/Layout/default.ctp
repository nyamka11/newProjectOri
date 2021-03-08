<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Hamamatsu Ori</title>
        <meta content="" name="descriptison">
        <meta content="" name="keywords">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS Files -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <?= $this->Html->css('font-awesome.min.css') ?>
        <?= $this->Html->css('style.css') ?>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    </head>

    <body>
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
        <?= $this->element('footer') ?>

        <!-- Vendor JS Files -->
        
        <?= $this->Html->script('main.js') ?>
        <?= $this->Html->script('display/function.js') ?>
        <?= $this->Html->script('home.js') ?>
        <?= $this->Html->script('display/chart.js') ?>
        
        <?= $this->Html->script('display/nutrient.js') ?>
        <?= $this->Html->script('display/requiredDemand.js') ?>

        <?= $this->Html->script('display/productRequestFree.js') ?>

        
        <?= $this->Html->script('display/supportRequestAutomaticCalculation.js') ?>
        <?= $this->Html->script('display/suppertListCreation.js') ?>
        <?= $this->Html->script('display/SupportDestinationSearch.js') ?>
        

    </body>

</html>