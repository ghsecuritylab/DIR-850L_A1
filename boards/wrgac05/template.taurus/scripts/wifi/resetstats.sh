#!/bin/sh
if [ "$1" = "ra0" -o "$1" = "ra1" ]; then
	iwpriv $1 set ResetCounter=1
else
	echo $1 > /proc/driver/ifresetcnt
fi
