<?php

namespace App\Enums;

enum Section: string
{
  case Header = 'header';
  case Sidebar = 'sidebar';
  case Content = 'content';
  case Full = 'full';
  case Footer = 'footer';
  case HeaderLeft = 'header-left';
  case HeaderMiddle = 'header-middle';
  case HeaderRight = 'header-right';
  case MiddleLeft = 'middle-left';
  case MiddleRight = 'middle-right';
  case FooterLeft = 'footer-left';
  case FooterMiddle = 'footer-middle';
  case FooterRight = 'footer-right';
}