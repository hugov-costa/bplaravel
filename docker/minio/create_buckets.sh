#!/bin/sh

mc mb myminio/${AWS_BUCKET_PUBLIC}
mc mb myminio/${AWS_BUCKET_PRIVATE}

mc policy set public myminio/${AWS_BUCKET_PUBLIC}
mc policy set private myminio/${AWS_BUCKET_PRIVATE}