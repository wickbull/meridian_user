<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>ФІСФМ</title>
    <link>{{ route('home') }}</link>
    <description>
        Факультет інформаційних систем, фізики та математики
    </description>
    <language>uk-UA</language>
    <lastBuildDate>{{ date('c') }}</lastBuildDate>

    @foreach ($items as $item)
      <item>
        <title>{{ $item->title }}</title>
        <link>{{ $item->getViewUrl() }}</link>
        <guid>{{ md5($item->id) }}</guid>
        <pubDate>{{ $item->publish_at->format('c') }}</pubDate>
        <description>{{ $item->getOgDescription() }}</description>
        <content:encoded>
          <![CDATA[
            {!! $item->body !!}
          ]]>
        </content:encoded>
      </item>
    @endforeach

  </channel>
</rss>
