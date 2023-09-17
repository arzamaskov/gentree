FROM php:8.1-cli

ARG USERNAME
ARG USERID
ARG GROUPID
ARG GROUPNAME

ARG APP_ROOT=/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir $APP_ROOT
WORKDIR $APP_ROOT

ENV UNAME=$USERNAME
ENV UID=$USERID
ENV GID=$GROUPID
ENV GNAME=$GROUPNAME

RUN addgroup $GNAME
RUN adduser --disabled-password --no-create-home --ingroup $GNAME $UNAME
RUN chown -R $UNAME:$GNAME /app

USER $UNAME

ADD . $APP_ROOT
ENV PATH=$APP_ROOT/bin:${PATH}
