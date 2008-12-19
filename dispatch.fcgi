#!/usr/bin/env ruby

require 'rubygems'
require 'ramaze'

Ramaze.skip_start

require File.join(File.dirname(__FILE__), 'start')

Ramaze::Log.loggers.clear
Ramaze.start! :root => __DIR__, :adapter => :fcgi
