<?php

namespace App\Http\Controllers;

use Aprillins\LiteGrabber\LiteGrabber;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Shape\Chart\Series;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Line;
use PhpOffice\PhpPresentation\Shape\Placeholder;

use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Slide\Transition;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Shadow;

class LabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {


        $objPHPPresentation = new PhpPresentation();


        $currentSlide = $objPHPPresentation->getActiveSlide();







        $oMasterSlide = $objPHPPresentation->getAllMasterSlides()[0];


        $oMasterSlide->createLineShape(0, 665, 960, 665)->getBorder()->setColor(new Color(Color::COLOR_BLACK))->setLineWidth(2);



        $shape = $oMasterSlide->createRichTextShape();
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_LEFT );
        $textRun = $shape->createTextRun('CIES GLOBAL');
        $textRun->getFont()->setBold(true)->setSize(20);


        $shape->setHeight(200);
        $shape->setWidth(600);
        $shape->setOffsetX(25);
        $shape->setOffsetY(674);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_LEFT );

//
//
//        $shape = $oMasterSlide->createDrawingShape();
//        $shape->setPath(public_path()."/src/image/logo/cies.png")
//
//            ->setHeight(36)
//            ->setOffsetX(5)
//            ->setOffsetY(674);
//
//        ;




        $shape = $currentSlide->createRichTextShape()
            ->setHeight(600)
            ->setWidth(930)
            ->setOffsetX(10)
            ->setOffsetY(130);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setMarginLeft(25)
            ->setIndent(-25);
        $shape->getActiveParagraph()->getFont()->setSize(36)
        //    ->setColor(Color::COLOR_BLACK)
        ;
        $shape->getActiveParagraph()->getBulletStyle()->setBulletType(Bullet::TYPE_BULLET);
        $shape->createTextRun('A class library');
        $shape->createParagraph()->createTextRun('Written in PHP');
        $shape->createParagraph()->createTextRun('Representing a presentation');
        $shape->createParagraph()->createTextRun('Supports writing to different file formats');


        $currentSlide = $objPHPPresentation->createSlide();


        $seriesData = array(
            'Monday' => 12,
            'Tuesday' => 15,
            'Wednesday' => 13,
            'Thursday' => 17,
            'Friday' => 14,
            'Saturday' => 9,
            'Sunday' => 7
        );

        $seriesData1 = array(
            'Monday' => 22,
            'Tuesday' => 25,
            'Wednesday' => 23,
            'Thursday' => 27,
            'Friday' => 24,
            'Saturday' => 19,
            'Sunday' => 17
        );

        $oFill = new Fill();
        $oFill->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FFE06B99'));
        $oShadow = new Shadow();
        $oShadow->setVisible(true)->setDirection(45)->setDistance(10);

        $lineChart = new Line();
        $series = new Series('Downloads', $seriesData);
        $series->setShowSeriesName(true);
        $series->setShowValue(true);
        $series->setFill(new Fill(new Color('FFE06B99')));
        $lineChart->addSeries($series);

        $series1 = new Series('Downloads', $seriesData1);
        $series1->setShowSeriesName(true);
        $series1->setShowValue(true);
        $lineChart->addSeries($series1);

        $shape = $currentSlide->createChartShape();
        $shape->setName('PHPPresentation Daily Downloads')->setResizeProportional(false)->setHeight(550)->setWidth(700)->setOffsetX(120)->setOffsetY(80);
        $shape->getBorder()->setLineStyle(Border::LINE_SINGLE);
        $shape->getTitle()->setText('PHPPresentation Daily Downloads');
        $shape->getTitle()->getFont()->setItalic(true);
        $shape->getPlotArea()->setType($lineChart);
        $shape->getView3D()->setRotationX(30);
        $shape->getView3D()->setPerspective(30);
        $shape->getLegend()->getBorder()->setLineStyle(Border::LINE_SINGLE);
        $shape->getLegend()->getFont()->setItalic(true);



        $currentSlide = $objPHPPresentation->createSlide();

        // Create a shape (text)
        $shape = $currentSlide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(180);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
        $textRun = $shape->createTextRun('Thank you for using PHPPresentation!');
        $textRun->getFont()->setBold(true)
            ->setSize(60)
            ->setColor( new Color( 'FFE06B20' ) );

    $currentSlide = $objPHPPresentation->createSlide();
        //$currentSlide->setBackground(new Color());

        $shape = $currentSlide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(180);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
        $textRun = $shape->createTextRun('Thank you for using PHPPresentation!');
        $textRun->getFont()->setBold(true)
            ->setSize(60)
            //    ->setColor(Color::COLOR_BLACK)
        ;




        $oWriterPPTX = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');

        $oWriterPPTX->save(public_path()  . "/sample.pptx");



    }

}
