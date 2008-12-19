#!/usr/bin/env ruby

require 'rubygems'
require 'ramaze'
require 'open-uri'
require 'hpricot'

class MainController < Ramaze::Controller
  URI = "http://api.wunderground.com/auto/wui/geo/%s/index.xml?query=%s"

  def index
    @t = (request[:t] || 'C').upcase
    @q = request[:q] || guess_location
    @c = @t == 'C'

    current      = Hpricot(open(URI % ['WXCurrentObXML', u(@q)]))
    @location    = current.at("current_observation/display_location/full")
    @temperature = current.at(@c ? 'temp_c' : "temp_f")
    @condition   = current.at("weather")

    forecast     = Hpricot(open(URI % ['ForecastXML', u(@q)]))
    @days        = forecast/"forecast/simpleforecast/forecastday/date/weekday/"
    @highs       = forecast/"forecast/simpleforecast/forecastday/high/#{@c ? 'celsius' : 'fahrenheit'}/"
    @lows        = forecast/"forecast/simpleforecast/forecastday/low/#{@c ? 'celsius' : 'fahrenheit'}/"
    @conditions  = forecast/"forecast/simpleforecast/forecastday/conditions/"
  end

  private

  # TODO: do something super smart in here.

  def guess_location
    'Tokyo'
  end
end

Ramaze.start :adapter => :thin
