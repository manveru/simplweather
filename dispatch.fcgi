#!/usr/local/bin/ruby

require 'rubygems'
require 'ramaze'
require 'open-uri'
require 'hpricot'

Ramaze::Log.loggers.clear
Ramaze::Global.adapter = :fcgi
Ramaze::Global.public_root = '/home/simplw/public_html'
Ramaze::Global.view_root = '/home/simplw/public_html/view'

class MainController < Ramaze::Controller
  def index
  end
  
  def zip
    @code = request['code']
    @current_weather     = Hpricot(open("http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query="+@code.to_s.gsub(/\s/, '%20')))
    @location            = @current_weather/"current_observation/display_location/full/"
    @current_temperature = @current_weather/"temp_f/"
    @current_condition   = @current_weather/"weather/"

    @weather_forecast    = Hpricot(open("http://api.wunderground.com/auto/wui/geo/ForecastXML/index.xml?query="+@code.to_s.gsub(/\s/, '%20')))
    @days                = @weather_forecast/"forecast/simpleforecast/forecastday/date/weekday/"
    @highs               = @weather_forecast/"forecast/simpleforecast/forecastday/high/fahrenheit/"
    @lows                = @weather_forecast/"forecast/simpleforecast/forecastday/low/fahrenheit/"
    @conditions          = @weather_forecast/"forecast/simpleforecast/forecastday/conditions/"
  end
end

Ramaze.start

__END__